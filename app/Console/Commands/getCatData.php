<?php

namespace App\Console\Commands;

use App\Models\AnimalData;
use App\Models\ShelterList;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class getCatData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:getCatData';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'getCatDatas';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        echo "程序開始" . PHP_EOL;
        echo "從資料庫拿出既有的貓咪資料" . PHP_EOL;
        $oldAnimalData = AnimalData::all()->toArray();
        $oldShelterData = ShelterList::all()->toArray();
        echo "獲取寵物領養資料..." . PHP_EOL;
        $response = Http::get('https://data.coa.gov.tw/Service/OpenData/TransService.aspx?UnitId=QcbUEzN6E6DL')->json();
        echo "過濾不是貓的寵物領養資料..." . PHP_EOL;
        $catData = [];
        foreach ($response as $item) {
            if ($item['animal_kind'] === '貓') {
                array_push($catData, $item);
            }
        };
        function addShelterData($shelterData)
        {
            echo "寫入{$shelterData['shelter_name']}的資料" . PHP_EOL;

            $key = env('MIX_API_KEY');

            $addressResponse = Http::get(
                "https://maps.googleapis.com/maps/api/place/findplacefromtext/json?input={$shelterData['shelter_name']}&inputtype=textquery&fields=geometry&key={$key}")->json();

            $shelterGeometry = $addressResponse['candidates'][0]['geometry']['location'];

            ShelterList::create(
                [
                    "shelter_name" => $shelterData['shelter_name'],
                    "shelter_city" => $shelterData['shelter_city'],
                    "shelter_address" => $shelterData['shelter_address'],
                    "shelter_tel" => $shelterData['shelter_tel'],
                    "shelter_lat" => $shelterGeometry['lat'],
                    "shelter_lng" => $shelterGeometry['lng'],
                ]);
        }
        function deleteOldAnimaData($id)
        {
            echo "刪除id名為{$id}的舊貓咪資料" . PHP_EOL;
            AnimalData::where('animal_id', $id)->delete();
        }
        function addNewAnimaData($catData)
        {
            echo "新建id名為{$catData['animal_id']}的新貓咪資料" . PHP_EOL;
            $shelter_id = ShelterList::select('id')->where('shelter_name', $catData['shelter_name'])->get()->toArray();
            AnimalData::create([
                "animal_id" => $catData['animal_id'],
                "animal_subid" => $catData['animal_subid'],
                "animal_sex" => $catData['animal_sex'],
                "animal_bodytype" => $catData['animal_bodytype'],
                "animal_color" => $catData['animal_color'],
                "animal_age" => $catData['animal_age'],
                "animal_sterilization" => $catData['animal_sterilization'],
                "animal_bacterin" => $catData['animal_bacterin'],
                "animal_foundplace" => $catData['animal_foundplace'],
                "animal_remark" => $catData['animal_remark'],
                "album_file" => $catData['album_file'],
                "shelter_id" => $shelter_id[0]['id'],
            ]);
        }
        function updateAlbumFile($id, $albumFile)
        {
            echo "更新id名為{$id}的照片資料" . PHP_EOL;
            AnimalData::where('animal_id', $id)->update(['album_file' => $albumFile]);
        }
        if (count($oldAnimalData) !== 0) {
            echo "開始比對新舊資料..." . PHP_EOL;
            foreach ($oldAnimalData as $oldData) {
                $id = $oldData['animal_id'];
                $arrayIndex = array_search($id, array_column($catData, 'animal_id'));
                if ($arrayIndex === false && $oldData['isFake'] === false) {
                    deleteOldAnimaData($id);
                }
                if ($arrayIndex !== false && $oldData['album_file'] === null && strlen($catData[$arrayIndex]['album_file']) !== 0) {
                    $albumFile = $catData[$arrayIndex]['album_file'];
                    updateAlbumFile($id, $albumFile);
                }
            }
        }

        echo "將新資料寫入資料庫..." . PHP_EOL;
        $isExist = [];
        foreach ($catData as $index => $cat) {
            $id = $cat['animal_id'];
            $shelterName = $cat['shelter_name'];
            $catDataArrayIndex = array_search($id, array_column($oldAnimalData, 'animal_id'));
            $shelterDataArrayIndex = array_search($shelterName, array_column($oldShelterData, 'shelter_name'));
            $isExistArrayIndex = in_array($shelterName, $isExist,true);
            if ($shelterDataArrayIndex === false && $isExistArrayIndex === false) {
                $newShelterData =
                    [
                    "shelter_name" => $cat['shelter_name'],
                    "shelter_address" => $cat['shelter_address'],
                    "shelter_city" => mb_substr($cat['shelter_address'], 0, 3, 'UTF-8'),
                    "shelter_tel" => $cat['shelter_tel'],
                ];
                array_push($isExist,$cat['shelter_name']);
                addShelterData($newShelterData);
            }
            if ($catDataArrayIndex === false) {
                $newCatData =
                    [
                    "animal_id" => $cat['animal_id'],
                    "animal_subid" => $cat['animal_subid'],
                    "animal_sex" => $cat['animal_sex'] === 'F' ? '女生' : '男生',
                    "animal_bodytype" => $cat['animal_bodytype'] === 'SMALL' ? '小型' : ($cat['animal_bodytype'] === 'MEDIUM' ? '中型' : '大型'),
                    "animal_color" => $cat['animal_colour'],
                    "animal_age" => $cat['animal_age'] === '' ? '歲數未知' : ($cat['animal_age'] === 'ADULT' ? '成年' : '幼年'),
                    "animal_sterilization" => $cat['animal_sterilization'] === 'F' ? '未絕育' : '已絕育',
                    "animal_bacterin" => $cat['animal_bacterin'] === 'F' ? '未打狂犬病疫苗' : '已打狂犬病疫苗',
                    "animal_foundplace" => $cat['animal_foundplace'],
                    "animal_remark" => $cat['animal_remark'],
                    "album_file" => $cat['album_file'] === '' ? null : $cat['album_file'],
                    "shelter_name" => $cat['shelter_name'],
                ];
                addNewAnimaData($newCatData);
            }
        }
        echo "程序結束" . PHP_EOL;
    }
}
