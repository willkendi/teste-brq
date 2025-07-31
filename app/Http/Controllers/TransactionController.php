<?php

namespace App\Http\Controllers;

use App\Services\TransactionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use App\Helpers\ErrorHandler;
use Illuminate\Support\Str;
use App\Http\Resources\TransactionResource;


class TransactionController extends Controller
{
    protected $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    public function index(Request $request)
    {
        $filters = $request->only(['start_date', 'end_date', 'status']);
        $transactions = $this->transactionService->filter($filters);

        return response()->json($transactions);
    }

    public function show($id)
    {
        if (!Str::isUuid($id)) {
            return response()->json(
                ['message' => 'ID informado é inválido.'],
                400,
                [],
                JSON_UNESCAPED_UNICODE
            );
        }

        $transaction = $this->transactionService->getById($id);
        return response()->json($transaction);
    }

    // Cadastrar nova transação
    public function store(Request $request)
    {
        $validated = $request->validate([
            'inscricao' => 'required|string|max:14',
            'tipo_inscricao' => 'required|in:cpf,cnpj',
            'valor' => 'required|numeric',
            'data_hora' => 'required|date',
            'localizacao' => 'required|string',
        ]);

        $transaction = $this->transactionService->create($validated);

        return (new TransactionResource($transaction))
            ->response()
            ->setStatusCode(201);
    }
}
