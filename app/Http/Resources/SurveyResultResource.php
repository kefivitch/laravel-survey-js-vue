<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class SurveyResultResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        //setlocale(LC_ALL, 'fr_FR', 'fr', 'FR');
        return [
            'id'            =>  $this->id,
            'json'          =>  $this->json,
            'ip_address'    =>  $this->ip_address,
            'created_at'    =>  $this->created_at->formatLocalized('%A %d %B %Y %H:%M:%S'),
            'user'          =>  $this->user,
        ];
    }
}
