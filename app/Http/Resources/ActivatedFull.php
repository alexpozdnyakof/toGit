<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Code as CodeResource;
use App\Http\Resources\Certificate as CertificateResource;


class ActivatedFull extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'company' => $this->company,
            'ceo' => $this->ceo,
            'ceo' => $this->ceo,
            'phone' => $this->phone,
            'email' => $this->email,
            'seller' => $this->seller,
            'status'  => $this->status,
            'tax'  => $this->tax,
            'created' => $this->created,
            'updated' => $this->updated,
            'code' => new CodeResource($this->whenLoaded('code')),
            'certs' => new CertificateResource($this->whenLoaded('certs')),
        ];
    }
}