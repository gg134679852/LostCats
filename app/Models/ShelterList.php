<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShelterList extends Model
{
    use HasFactory;
    protected $table = 'shelter_list';
    protected $guarded = [];
    public function cat()
    {
        return $this->hasMany(AnimalData::class, 'shelter_id');
    }
}
