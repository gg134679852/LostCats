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
}
