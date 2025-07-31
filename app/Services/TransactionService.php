<?php

namespace App\Services;

use App\Models\Transaction;
use App\Repositories\TransactionRepository;
use Carbon\Carbon;

class TransactionService
{
    protected TransactionRepository $transactionRepository;

    public function __construct(TransactionRepository $transactionRepository)
    {
        $this->transactionRepository = $transactionRepository;
    }

    public function create(array $data): Transaction
    {
        $hora = Carbon::now('America/Sao_Paulo')->hour;
        $valor = $data['valor'];

        if (
            $valor > 1000 &&
            ($hora >= 22 || $hora < 6)
        ) {
            $data['status'] = 'alto_risco';
            $data['motivo_risco'] = 'Valor acima de 1000 entre 22h e 6h';
        } else {
            $data['status'] = $data['status'] ?? 'normal';
        }

        $data['data_hora'] = Carbon::now('America/Sao_Paulo')->toDateTimeString();

        return $this->transactionRepository->create($data);
    }


    public function getById(string $id): Transaction
    {
        return $this->transactionRepository->findById($id);
    }

    public function filter(array $filters)
    {
        return $this->transactionRepository->filter($filters);
    }
}
