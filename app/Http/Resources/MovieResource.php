<?php

namespace App\Http\Resources;

use App\Http\Resources\GenreResource;
use Illuminate\Http\Resources\Json\JsonResource;

class MovieResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "id"        => $this->id,
            "name"      => $this->name,
            "overview"  => $this->overview,
            "tmdb_type" => $this->tmdb_type,
            "genres"    => optional($this->genres) ? GenreResource::collection($this->genres) : ['asd'],
        ];
    }
}
