<?php

namespace App\Repositories;

use App\Models\Transaction;

class TransactionRepository
{
    public function create(array $data): Transaction
    {
        return Transaction::create($data);
    }

    public function findById(string $id): Transaction
    {
        return Transaction::findOrFail($id);
    }

    public function filter(array $filters)
    {
        $query = Transaction::query();

        if (isset($filters['start_date']) && isset($filters['end_date'])) {
            $query->whereBetween('data_hora', [$filters['start_date'], $filters['end_date']]);
        }

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->get();
    }
}
