<?php

namespace App\Http\Controllers;

use App\Http\Resources\AnimalDataResource;
use App\Models\AnimalData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class animalDataController extends Controller
{
    public function getAnimalData()
    {
        $short_address = AnimalData::select('short_address')->get()->toArray();
        $animal_color = AnimalData::select('animal_color')->get()->toArray();
        $responseData = AnimalData::paginate(18);
        $shortAddress = [];
        $color = [];

        foreach ($short_address as $data) {
            if (in_array($data['short_address'], $shortAddress) !== true) {
                array_push($shortAddress, $data['short_address']);
            }
        }
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
                    'selectOption' => ['shortAddress' => $shortAddress, 'color' => $color]],
            ]);
    }

    public function getAnimalDataFilter($address, $sex, $color)
    {

        $requestData = ["short_address" => $address, "animal_sex" => $sex, "animal_color" => $color];

        $whereData = array_filter($requestData, function ($item) {return $item !== "0";});

        $responseData =
        AnimalData::where($whereData)->paginate(18);

        return response()->json(
            [
                'success' => 'true',
                'responseData' => [
                    'catData' => $responseData],
            ]);

    }

    public function getAnimalDetailData($id, $address)
    {
        $key = env('MIX_API_KEY');

        $addressResponse = Http::get(
            "https://maps.googleapis.com/maps/api/place/findplacefromtext/json?input={$address}&inputtype=textquery&fields=geometry&key={$key}")->json();

        $AnimalData = new AnimalDataResource(AnimalData::findOrFail($id));

        return [$AnimalData, $addressResponse];

    }
    public function createCatData(Request $request)
    {
        return dd($request);
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
                'success' => 'true',
                'image' => $responseData["data"]["link"],
            ]);
        } else {
            return response()->json([
                'success' => 'false',
            ]);

        }
    }

}
