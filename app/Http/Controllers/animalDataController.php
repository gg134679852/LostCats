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
    public function getAnimalDataFilter()
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
    public function postAnimalDataFilter(Request $request)
    {
        $requestData = $request->input();
         
         $requestData = array_filter($requestData);

         $requestData = array_chunk($requestData, 1, true);
          
         $responesData = [];

         if(count($requestData)=== 1){
            $column = key($requestData['0']);
            $keyWord = pos($requestData['0']);
            $responesData = 
            new AnimalDataCollection(
            AnimalData::where($column,$keyWord)->paginate(16));
            return $responesData ;
         }

            if (count($requestData) === 2) {
                $column0 = key($requestData['0']);
                $keyWord0 = pos($requestData['0']);
                $column1 = key($requestData['1']);
                $keyWord1 = pos($requestData['1']);

                $responesData =
                new AnimalDataCollection(
                    AnimalData::where($column0, $keyWord0)->where($column1, $keyWord1)->paginate(16));
                return $responesData;
            }
    if (count($requestData) === 3) {
    $column0 = key($requestData['0']);
    $keyWord0 = pos($requestData['0']);
    $column1 = key($requestData['1']);
    $keyWord1 = pos($requestData['1']);
    $column2 = key($requestData['2']);
    $keyWord2 = pos($requestData['2']);


    $responesData =
    new AnimalDataCollection(
        AnimalData::where($column0, $keyWord0)->where($column1, $keyWord1)->where($column2, $keyWord2)->paginate(16));
    return $responesData;
}


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
