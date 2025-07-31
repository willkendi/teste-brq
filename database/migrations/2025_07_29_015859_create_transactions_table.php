<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('inscricao', 14);
            $table->enum('tipo_inscricao', ['cpf', 'cnpj']);
            $table->decimal('valor', 15, 2);
            $table->timestamp('data_hora');
            $table->string('localizacao')->nullable();
            $table->string('status');
            $table->string('motivo_risco')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
