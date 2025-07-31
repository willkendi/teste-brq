<?php

namespace App\Http\Controllers;

use App\Services\TransactionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
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
        try {
            $filters = $request->only(['start_date', 'end_date', 'status']);
            $transactions = $this->transactionService->filter($filters);

            return TransactionResource::collection($transactions);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao buscar transações.',
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            if (!Str::isUuid($id)) {
                return response()->json(
                    ['message' => 'ID informado é inválido.'],
                    400,
                    [],
                    JSON_UNESCAPED_UNICODE
                );
            }

            $transaction = $this->transactionService->getById($id);

            if (!$transaction) {
                return response()->json(['message' => 'Transação não encontrada.'], 404);
            }

            return new TransactionResource($transaction);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao buscar transação.',
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'inscricao' => 'required|string|max:14',
                'tipo_inscricao' => 'required|in:cpf,cnpj',
                'valor' => 'required|numeric',
                'localizacao' => 'required|string',
            ]);

            $transaction = $this->transactionService->create($validated);

            return (new TransactionResource($transaction))
                ->response()
                ->setStatusCode(201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Dados inválidos.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {

            return response()->json([
                'message' => 'Erro ao criar transação.',
            ], 500);
        }
    }
}
