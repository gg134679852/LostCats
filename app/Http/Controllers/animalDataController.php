<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\AnimalDataResource;
use App\Http\Resources\AnimalDataCollection;
use App\Models\AnimalData;
class animalDataController extends Controller
{
    function getAnimalData(){
      return new AnimalDataCollection(AnimalData::paginate());
    }
}
