<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Services\TransactionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Transaction;
use Illuminate\Support\Collection;

class TransactionApiTest extends TestCase
{
    private $token = 'token-teste';

    protected function setUp(): void
    {
        parent::setUp();

        $transactionExample = new Transaction([
            'id' => '550e8400-e29b-41d4-a716-446655440000',
            'inscricao' => '12345678901',
            'tipo_inscricao' => 'cpf',
            'valor' => 100,
            'data_hora' => now(),
            'localizacao' => 'São Paulo',
            'status' => 'paid',
        ]);

        $transactionNew = new Transaction([
            'id' => '550e8400-e29b-41d4-a716-446655440002',
            'inscricao' => '12345678901',
            'tipo_inscricao' => 'cpf',
            'valor' => 150,
            'data_hora' => now(),
            'localizacao' => 'São Paulo',
            'status' => 'paid',
        ]);

        $mock = $this->createMock(TransactionService::class);

        $mock->method('filter')
            ->willReturn($transactionExample);

        $mock->method('getById')
            ->willReturn($transactionExample);

        $mock->method('create')
            ->willReturn($transactionNew);

        $this->app->instance(TransactionService::class, $mock);
    }

    public function test_index_requires_token()
    {
        $response = $this->getJson('/api/transactions');
        $response->assertStatus(401);
    }

    public function test_index_with_valid_token()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/transactions');

        $response->assertStatus(200)
            ->assertJsonFragment(['inscricao' => '12345678901']);
    }

public function test_show_with_valid_token()
{
    $validUuid = '550e8400-e29b-41d4-a716-446655440000';

    $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $this->token,
    ])->getJson("/api/transactions/{$validUuid}");

    $response->assertStatus(200)
             ->assertJsonStructure([
                 'data' => [
                     'inscricao',
                     'tipo_inscricao',
                     'valor',
                     'data_hora',
                     'localizacao',
                 ]
             ]);

    $tipo = $response->json('data.tipo_inscricao');
    $this->assertTrue(in_array($tipo, ['cpf', 'cnpj']), "tipo_inscricao deve ser 'cpf' ou 'cnpj'");
}

    public function test_store_valid_data()
    {
        $data = [
            'inscricao' => '12345678901',
            'tipo_inscricao' => 'cpf',
            'valor' => 100.5,
            'data_hora' => '2025-07-31 10:00:00',
            'localizacao' => 'São Paulo',
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/transactions', $data);

        $response->assertStatus(201)
            ->assertJsonFragment(['valor' => 150]);
    }

    public function test_store_invalid_data()
    {
        $data = [
            'inscricao' => '', // obrigatório
            'tipo_inscricao' => 'invalid',
            'valor' => 'not-a-number',
            'data_hora' => 'invalid-date',
            'localizacao' => '',
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/transactions', $data);

        $response->assertStatus(422)
            ->assertJsonStructure(['message', 'errors']);
    }
}
