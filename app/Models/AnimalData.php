<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class AnimalData extends Model
{
    use HasFactory;

   protected $guarded = [];

   public function user()
   {
       return $this->belongsToMany(User::class, 'favorite_cats');

   }
   public function shelter()
   {
       return $this->belongsTo(ShelterList::class);
   }
}
