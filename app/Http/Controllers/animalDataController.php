<?php

namespace App\Http\Controllers;

use App\Http\Resources\AnimalDataCollection;
use App\Http\Resources\AnimalDataResource;
use Illuminate\Http\Request;

use App\Models\AnimalData;
use Illuminate\Support\Facades\Http;

class animalDataController extends Controller
{
    public function getAnimalData()
    {
        $animalData = new AnimalDataCollection( AnimalData::paginate(16));

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

      return ['shortAddress'=>$shortAddress,
       'color'=>$color];
    }
    public function getAnimalDataFilter($address,$sex,$colour)
    {
        
    $responesData =
    new AnimalDataCollection(
        AnimalData::where('short_address',$address)->where('animal_sex', $sex)->where('animal_colour', $colour)->paginate(16));

    return $responesData;
}


    
    public function getAnimalDetailData($id, $address)
    {
        $key = config('keys.mapKEY');

        $addressResponse = Http::get(
            "https://maps.googleapis.com/maps/api/place/findplacefromtext/json?input={$address}&inputtype=textquery&fields=geometry&key={$key}")->json();
            
        $AnimalData = new AnimalDataResource(AnimalData::findOrFail($id));

        return [$AnimalData, $addressResponse];

    }

}
