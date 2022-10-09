<?php

namespace App\Http\Controllers;

use App\Models\AnimalData;
use App\Models\ShelterList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class animalDataController extends Controller
{
    public function dataLengthCalculator($screenSize)
    {
        $dataLength = '';

        switch ($screenSize) {
            case 'Small':
                $dataLength = 16;
                break;

            case 'Middle':
                $dataLength = 18;
                break;

            case 'Big':
                $dataLength = 20;
                break;
        };
        return $dataLength;
    }
    public function getAnimalData(Request $request)
    {
        $animal_color = AnimalData::select('animal_color')->get()->toArray();
        $shelter_list = ShelterList::get()->toArray();
        $requestData = $request->all();
        $color = [];
        $screenSize = $requestData['screenSize'];
        $dataLength = $this->dataLengthCalculator($screenSize);

        $responseData = AnimalData::with('shelter')->paginate($dataLength)->withQueryString();

        foreach ($animal_color as $data) {

            if (in_array($data['animal_color'], $color) !== true) {
                array_push($color, $data['animal_color']);
            }
        }

        return response()->json(
            [
                'success' => 'true',
                'responseData' => [
                    'catData' => $responseData,
                    'shelterList' => $shelter_list,
                    'selectOption' => ['color' => $color],
                ],
            ]);
    }

    public function getAnimalDataFilter(Request $request)
    {
        $allRequestData = $request->all();
        $screenSize = $allRequestData['screenSize'];
        $haveImage = $allRequestData['haveImage'];
        $dataLength = $this->dataLengthCalculator($screenSize);
        $requestCatData = ["animal_sex" => $allRequestData['animal_sex'], "animal_color" => $allRequestData['animal_color']];
        $requestShelterData = ["shelter_city" => $allRequestData['shelter_city'], "shelter_name" => $allRequestData['shelter_name']];

        $catDataWhereQuery = array_filter($requestCatData, function ($item) {return $item !== "0";});
        $shelterDataWhereQuery = array_filter($requestShelterData, function ($item) {return $item !== "0";});

        $findShelter = ShelterList::select('id')->where($shelterDataWhereQuery);

        $findCat = $findShelter->with(['cat' => function ($q) use ($catDataWhereQuery) {$q->where($catDataWhereQuery)->with('shelter');}])->get()->pluck('cat')->collapse();

        switch ($haveImage) {
            case 'notNull':
                $responseCatData = $findCat->whereNotNull('album_file')->paginate($dataLength)->withQueryString();
                break;
            case 'Null':
                $responseCatData = $findCat->whereNull('album_file')->paginate($dataLength)->withQueryString();
                break;
            default:
                $responseCatData = $findCat->paginate($dataLength)->withQueryString();
                break;
        }

        return response()->json(
            [
                'success' => 'true',
                'responseData' => [
                    'catData' => $responseCatData],
            ]);
    }

    public function createNewCatData(Request $request)
    {
        $allRequestData = $request->all();
        $validatedData = $request->validateWithBag('post', [
            "album_file" => [],
            "animal_age" => ['required'],
            "animal_bacterin" => ['required'],
            "animal_bodytype" => ['required'],
            "animal_color" => ['required'],
            "animal_foundplace" => ['required'],
            "animal_remark" => [],
            "animal_sex" => ['required'],
            "animal_sterilization" => ['required'],
            "shelter_id" => ['required'],
        ]);

        $animalId = AnimalData::where('shelter_id', $allRequestData['shelter_id'])->max('animal_id');

        $createNewCatData = AnimalData::create([
            "animal_id" => $animalId + 1,
            "album_file" => $validatedData["album_file"],
            "animal_age" => $validatedData["animal_age"],
            "animal_bacterin" => $validatedData["animal_bacterin"],
            "animal_bodytype" => $validatedData["animal_bodytype"],
            "animal_color" => $validatedData["animal_color"],
            "animal_foundplace" => $validatedData["animal_foundplace"],
            "animal_remark" => $validatedData["animal_remark"],
            "animal_sex" => $validatedData["animal_sex"],
            "animal_sterilization" => $validatedData["animal_sterilization"],
            "shelter_id" => $validatedData["shelter_id"],
        ])->save();
        if ($createNewCatData) {
            return response()->json([
                'icon' => 'success',
                'message' => '創建檔案成功',
            ]);
        } else {
            return response()->json([
                'icon' => 'error',
                'message' => '創建檔案失敗，請排除錯誤',
            ]);
        }
    }

    public function uploadImage(Request $request)
    {
        $image = base64_encode(file_get_contents($request->file('image')->path()));
        $imgurId = env("IMGUR_CLIENT_ID");
        $client = new \GuzzleHttp\Client();
        $response = $client->request('POST', 'https://api.imgur.com/3/image', [
            'headers' => [
                'authorization' => 'Client-ID ' . $imgurId,
                'content-type' => 'application/x-www-form-urlencoded',
            ],
            'form_params' => [
                'image' => $image,
            ],
        ]);

        $responseData = json_decode(($response->getBody()), true);

        if ($responseData['success'] === true) {
            return response()->json([
                'icon' => 'success',
                'message' => '上傳成功',
                'image' => $responseData["data"]["link"],
            ]);
        } else {
            return response()->json([
                'icon' => 'error',
                'message' => '上傳失敗，請排除錯誤',
            ]);
        }
    }

    public function updateCatData(Request $request)
    {
        $validatedData = $request->validateWithBag('post', [
            "animal_id" => ['required'],
            "album_file" => [],
            "animal_age" => ['required'],
            "animal_bacterin" => ['required'],
            "animal_bodytype" => ['required'],
            "animal_color" => ['required'],
            "animal_foundplace" => ['required'],
            "animal_remark" => [],
            "animal_sex" => ['required'],
            "animal_sterilization" => ['required'],
            "shelter_address" => ['required'],
            "shelter_name" => ['required'],
            "shelter_tel" => ['required'],
        ]);

        $id = $request->input('id');

        $updateCatData = AnimalData::where('id', $id)->update($validatedData);

        if ($updateCatData === 1) {
            return response()->json([
                'icon' => 'success',
                'message' => '更新檔案成功',
            ]);
        } else {
            return response()->json([
                'icon' => 'error',
                'message' => '更新檔案失敗，請排除錯誤',
            ]);
        }
    }

    public function deleteCatData(Request $request)
    {
        $id = $request->input('id');
        $deleteCatData = AnimalData::where('animal_id', $id)->delete();

        if ($deleteCatData === 1) {
            return response()->json([
                'icon' => 'success',
                'message' => '刪除檔案成功',
            ]);
        } else {
            return response()->json([
                'icon' => 'error',
                'message' => '刪除檔案失敗，請排除錯誤',
            ]);
        }

    }

    public function createNewShelterData(Request $request)
    {
        $validatedData = $request->validateWithBag('post', [
            "shelter_name" => ['required'],
            "shelter_city" => ['required'],
            "shelter_address" => ['required'],
            "shelter_tel" => ['required'],
        ]);

        $oldShelterData = ShelterList::where('shelter_name', $validatedData['shelter_name'])->get()->toArray();

        if (count($oldShelterData) !== 0) {
            return response()->json([
                'icon' => 'error',
                'message' => '收容所已存在',
            ]);
        }

        $key = env('MIX_API_KEY');

        $addressResponse = Http::get(
            "https://maps.googleapis.com/maps/api/place/findplacefromtext/json?input={$validatedData['shelter_address']}&inputtype=textquery&fields=geometry&key={$key}")->json();

        $shelterGeometry = $addressResponse['candidates'][0]['geometry']['location'];

        $createNewShelterData = ShelterList::create([
            "shelter_name" => $validatedData["shelter_name"],
            "shelter_city" => $validatedData["shelter_city"],
            "shelter_address" => $validatedData["shelter_address"],
            "shelter_tel" => $validatedData["shelter_tel"],
            "shelter_lat" => $shelterGeometry['lat'],
            "shelter_lng" => $shelterGeometry['lng'],
        ])->save();
        if ($createNewShelterData) {
            return response()->json([
                'icon' => 'success',
                'message' => '創建檔案成功',
            ]);
        } else {
            return response()->json([
                'icon' => 'error',
                'message' => '創建檔案失敗，請排除錯誤',
            ]);
        }
    }

    public function updateShelterData(Request $request)
    {
        $validatedData = $request->validateWithBag('post', [
            "shelter_name" => ['required'],
            "shelter_city" => ['required'],
            "shelter_address" => ['required'],
            "shelter_tel" => ['required'],
        ]);
        $key = env('MIX_API_KEY');

        $addressResponse = Http::get(
            "https://maps.googleapis.com/maps/api/place/findplacefromtext/json?input={$validatedData['shelter_address']}&inputtype=textquery&fields=geometry&key={$key}")->json();

        $shelterGeometry = $addressResponse['candidates'][0]['geometry']['location'];

        $id = $request->input('id');

        $updateCatData = ShelterList::where('id', $id)->update([
            "shelter_name" => $validatedData["shelter_name"],
            "shelter_city" => $validatedData["shelter_city"],
            "shelter_address" => $validatedData["shelter_address"],
            "shelter_tel" => $validatedData["shelter_tel"],
            "shelter_lat" => $shelterGeometry['lat'],
            "shelter_lng" => $shelterGeometry['lng'],
        ]);

        if ($updateCatData === 1) {
            return response()->json([
                'icon' => 'success',
                'message' => '更新檔案成功',
            ]);
        } else {
            return response()->json([
                'icon' => 'error',
                'message' => '更新檔案失敗，請排除錯誤',
            ]);
        }
    }

    public function deleteShelterData(Request $request)
    {
        $id = $request->input('id');
        $dataCount = AnimalData::where('shelter_id', $id)->count();
        if ($dataCount !== 0) {
            return response()->json([
                'icon' => 'error',
                'message' => '刪除檔案失敗，請先處理關聯資料',
            ]);
        }
        $deleteShelterData = ShelterList::where('id', $id)->delete();

        if ($deleteShelterData === 1) {
            return response()->json([
                'icon' => 'success',
                'message' => '刪除檔案成功',
            ]);
        } else {
            return response()->json([
                'icon' => 'error',
                'message' => '刪除檔案失敗，請排除錯誤',
            ]);
        }

    }
}
