<?php

namespace App\Console\Commands;

use App\Models\AnimalData;
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

        AnimalData::truncate();

        $response = Http::get('https://data.coa.gov.tw/Service/OpenData/TransService.aspx?UnitId=QcbUEzN6E6DL')->json();

        $catData = array_filter($response, function ($item) {
            return ($item['animal_kind'] === '貓');
        });
        foreach ($catData as $cat) {
            AnimalData::create([
                "animal_id" => $cat['animal_id'],
                "animal_sex" => $cat['animal_sex'] === 'F' ? '女生' : '男生',
                "animal_bodytype" => $cat['animal_bodytype'],
                "animal_colour" => $cat['animal_colour'],
                "animal_age" => $cat['animal_age'] === '' ? '歲數未知' : $cat['animal_age'],
                "animal_sterilization" => $cat['animal_sterilization'] === 'F' ? '未絕育' : '已絕育',
                "animal_bacterin" => $cat['animal_bacterin'] === 'F' ? '未打狂犬病疫苗' : '已打狂犬病疫苗',
                "animal_foundplace" => $cat['animal_foundplace'],
                "animal_remark" => $cat['animal_remark'],
                "shelter_name" => $cat['shelter_name'],
                "album_file" => $cat['album_file'],
                "shelter_address" => $cat['shelter_address'],
                'short_address' => mb_substr($cat['shelter_address'], 0, 3, 'UTF-8'),
                "shelter_tel" => $cat['shelter_tel'],
            ]);
        };
    }

}
