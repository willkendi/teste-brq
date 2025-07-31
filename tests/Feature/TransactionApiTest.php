<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Transaction;

class TransactionApiTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_returns_list_of_transactions()
    {
        Transaction::factory()->count(3)->create();

        $response = $this->getJson('/api/transactions');

        $response->assertStatus(200);
        $response->assertJsonCount(3);
        $response->assertJsonStructure([
            '*' => [
                'id',
                'inscricao',
                'tipo_inscricao',
                'valor',
                'data_hora',
                'localizacao',
                'created_at',
                'updated_at',
            ],
        ]);
    }

    /** @test */
    public function it_returns_single_transaction()
    {
        $transaction = Transaction::factory()->create();

        $response = $this->getJson('/api/transactions/' . $transaction->id);

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'id' => $transaction->id,
        ]);
    }

    /** @test */
    public function it_returns_404_for_non_existing_transaction()
    {
        $response = $this->getJson('/api/transactions/00000000-0000-0000-0000-000000000000');

        $response->assertStatus(404);
        $response->assertJson([
            'message' => 'Registro não encontrado.',
        ]);
    }

    /** @test */
    public function it_creates_a_transaction_with_valid_data()
    {
        $payload = [
            'inscricao' => '12345678901',
            'tipo_inscricao' => 'cpf',
            'valor' => 150.75,
            'data_hora' => now()->toDateTimeString(),
            'localizacao' => 'São Paulo',
        ];

        $response = $this->postJson('/api/transactions', $payload);

        $response->assertStatus(201);
        $response->assertJsonFragment([
            'inscricao' => '12345678901',
            'tipo_inscricao' => 'cpf',
            'valor' => 150.75,
            'localizacao' => 'São Paulo',
        ]);

        $this->assertDatabaseHas('transactions', [
            'inscricao' => '12345678901',
        ]);
    }

    /** @test */
    public function it_validates_required_fields_on_create()
    {
        $response = $this->postJson('/api/transactions', []);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors([
            'inscricao',
            'tipo_inscricao',
            'valor',
            'data_hora',
            'localizacao',
        ]);
    }

    /** @test */
    public function it_rejects_invalid_tipo_inscricao()
    {
        $payload = [
            'inscricao' => '12345678901',
            'tipo_inscricao' => 'invalid_type',
            'valor' => 100,
            'data_hora' => now()->toDateTimeString(),
            'localizacao' => 'São Paulo',
        ];

        $response = $this->postJson('/api/transactions', $payload);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('tipo_inscricao');
    }
}
