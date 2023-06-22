<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class EventResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            // 'start' => $this->start,
            // 'end' => $this->end,

            'start' => Carbon::parse($this->start)->format('Y-m-d H:i:s'),
            'end' => Carbon::parse($this->end)->format('Y-m-d H:i:s'),

            'description' => $this->description,
            'backgroundColor' => $this->back_color,
        ];
    }
}
