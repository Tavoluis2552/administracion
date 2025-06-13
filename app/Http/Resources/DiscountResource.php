<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
//use Carbon\Carbon;

class DiscountResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'description' => $this->description,
            'percentage' => $this->percentage,
            'state' => $this->state,
            //'created_at' => Carbon::parse($this->created_at)->setTimezone('America/Lima')->format('Y-m-d H:i:s'),
            //'updated_at' => Carbon::parse($this->updated_at)->setTimezone('America/Lima')->format('Y-m-d H:i:s'),
        ];
    }
}