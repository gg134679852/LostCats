<?php

namespace App\Http\Controllers;

use App\Models\AnimalData;
use App\Models\ShelterList;
use Illuminate\Http\Request;

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
        $createNewCatData = AnimalData::create([
            "animal_id" => $validatedData["animal_id"],
            "album_file" => $validatedData["album_file"],
            "animal_age" => $validatedData["animal_age"],
            "animal_bacterin" => $validatedData["animal_bacterin"],
            "animal_bodytype" => $validatedData["animal_bodytype"],
            "animal_color" => $validatedData["animal_color"],
            "animal_foundplace" => $validatedData["animal_foundplace"],
            "animal_remark" => $validatedData["animal_remark"],
            "animal_sex" => $validatedData["animal_sex"],
            "animal_sterilization" => $validatedData["animal_sterilization"],
            "shelter_address" => $validatedData["shelter_address"],
            'short_address' => mb_substr($validatedData['shelter_address'], 0, 3, 'UTF-8'),
            "shelter_name" => $validatedData["shelter_name"],
            "shelter_tel" => $validatedData["shelter_tel"],
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
}
