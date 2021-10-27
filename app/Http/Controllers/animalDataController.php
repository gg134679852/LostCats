<?php

namespace App\Http\Controllers;

use App\Http\Resources\AnimalDataCollection;
use App\Http\Resources\AnimalDataResource;
use App\Models\AnimalData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class animalDataController extends Controller
{
    public function getAnimalData()
    {
        $animalData = new AnimalDataCollection(AnimalData::paginate(16));

        return $animalData;
    }
    public function getAnimalDataSelect()
    {
        $filterData = AnimalData::all();
        $shortAddress = [];
        $color = [];

        foreach ($filterData as $data) {
            if (in_array($data['short_address'], $shortAddress)) {
            } else {
                array_push($shortAddress, $data['short_address']);
            }

            if (in_array($data['animal_colour'], $color)) {
            } else {
                array_push($color, $data['animal_colour']);
            }
        }
        $color = array_filter($color);

        return ['shortAddress' => $shortAddress,
            'color' => $color];
    }

    public function getAnimalDataFilter($address, $sex, $colour)
    {
        $requestData = [
            'short_address' => $address,
            'animal_sex' => $sex,
            'animal_colour' => $colour,
        ];

        $requestData = array_filter($requestData);

        $requestData = array_chunk($requestData, 1, true);

        $responesData = [];

        if (count($requestData) === 0) {
            $responesData =
            new AnimalDataCollection(
                AnimalData::paginate(16));
        }

        if (count($requestData) === 1) {
            $responesData =
            new AnimalDataCollection(
                AnimalData::where(key($requestData['0']), pos($requestData['0']))->paginate(16));
        }

        if (count($requestData) === 2) {
            $responesData =
            new AnimalDataCollection(
                AnimalData::where(key($requestData['0']), pos($requestData['0']))->where(key($requestData['1']), pos($requestData['1']))->paginate(16));
        }

        if (count($requestData) === 3) {
            $responesData =
            new AnimalDataCollection(
                AnimalData::where(key($requestData['0']), pos($requestData['0']))->where(key($requestData['1']), pos($requestData['1']))->where(key($requestData['2']), pos($requestData['2']))->paginate(16));
        }

        return $responesData;
    }

    public function getAnimalDetailData($id, $address)
    {
        $key = env('MIX_API_KEY');

        $addressResponse = Http::get(
            "https://maps.googleapis.com/maps/api/place/findplacefromtext/json?input={$address}&inputtype=textquery&fields=geometry&key={$key}")->json();

        $AnimalData = new AnimalDataResource(AnimalData::findOrFail($id));

        return [$AnimalData, $addressResponse];

    }

}
