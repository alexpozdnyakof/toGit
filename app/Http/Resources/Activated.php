<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Activated extends JsonResource
{

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'company' => $this->company,
            'status'  => $this->status,
            'tax'  => $this->tax,
            'created_at' => $this->created,
            'updated_at' => $this->updated,
        ];
    }
}
