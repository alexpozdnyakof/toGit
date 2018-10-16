<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Certificate extends JsonResource
{

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description'  => $this->description,
            'price'  => $this->price
            // 'invoice' => $this->invoice_template,
            // 'template' => $this->template,
        ];
    }
}
