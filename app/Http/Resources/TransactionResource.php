<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'inscricao' => $this->inscricao,
            'tipo_inscricao' => $this->tipo_inscricao,
            'valor' => $this->valor,
            'data_hora' => $this->data_hora,
            'localizacao' => $this->localizacao,
        ];
    }
}
