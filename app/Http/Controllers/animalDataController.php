<?php

namespace App\Http\Controllers;

use App\Http\Resources\AnimalDataCollection;
use App\Http\Resources\AnimalDataResource;
use App\Models\AnimalData;
use Illuminate\Support\Facades\Http;

class animalDataController extends Controller
{
    public function getAnimalData()
    {
        return new AnimalDataCollection(AnimalData::paginate(16));
    }
    public function getAnimalDetailData($id, $address)
    {
        $env = env('MIX_API_KEY');

        $addressResponse = Http::get(
            "https://maps.googleapis.com/maps/api/place/findplacefromtext/json?input={$address}&inputtype=textquery&fields=geometry&key={$env}")->json();

        $AnimalData = new AnimalDataResource(AnimalData::findOrFail($id));

        return [$AnimalData, $addressResponse];

    }

}
