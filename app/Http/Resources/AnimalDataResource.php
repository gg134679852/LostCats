<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AnimalDataResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public static $wrap = '';

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            "animal_id" =>
            $this->animal_id,
                "animal_place" => $this->animal_place,
                "animal_sex" => $this->animal_sex,
                "animal_bodytype" => $this->animal_bodytype,
                "animal_colour" => $this->animal_colour,
                "animal_age" => $this->animal_age,
                "animal_sterilization" => $this->animal_sterilization,
                "animal_bacterin" => $this->animal_bacterin,
                "animal_foundplace" => $this->animal_foundplace,
                "animal_remark" => $this->animal_remark,
                "shelter_name" => $this->shelter_name,
                "album_file" => $this->album_file,
                "shelter_address" => $this->shelter_address,
                "short_address" =>$this->short_address,
                "shelter_tel" => $this->shelter_tel
        ];
    }
}
