<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Activated as ActivatedResource;
use App\Http\Resources\ActivatedFull as ActivatedFullResource;


class Code extends JsonResource
{

    public function toArray($request)
    {
        return [
            'serial_code' => $this->serial_code,
            'activated_code' => $this->activated_code,
            'type' => $this->type,
            'activated' => new ActivatedFullResource($this->whenLoaded('store')),
           /* 'company' => $this->store->company,
            'company' => $this->store->company,
            'status'  => $this->store->status,
            'tax'  => $this->store->tax,
            'created_at' => $this->store->created,
            'updated_at' => $this->store->updated,
            */
        ];
    }
}