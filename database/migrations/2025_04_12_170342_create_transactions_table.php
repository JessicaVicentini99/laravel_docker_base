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
            $table->id();
            $table->decimal('amount', 20, 2); // valor da transação
            $table->boolean('is_received');   // true = recebida, false = enviada
            $table->unsignedBigInteger('from_user_id')->nullable(); // id do usuário de origem
            $table->unsignedBigInteger('to_user_id');   // id do usuário de destino
            $table->string('status');   // id do usuário de destino
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('from_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('to_user_id')->references('id')->on('users')->onDelete('cascade');
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
