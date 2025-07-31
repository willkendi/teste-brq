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
            'id' => $this->id,
            'inscricao' => $this->inscricao,
            'tipo_inscricao' => $this->tipo_inscricao,
            'valor' => $this->valor,
            'localizacao' => $this->localizacao,
            'status' => $this->status,
            'motivo_risco' => $this->motivo_risco
        ];
    }
}
