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
            $table->string('invoice_id');
            $table->enum('type', ['debit', 'credit'])->nullable();
            $table->integer('category_id');
            $table->string('payer');
            $table->string('payee');
            $table->integer('payer_id')->nullable();
            $table->integer('payee_id')->nullable();
            $table->string('amount')->default('0');
            $table->string('note')->nullable();
            $table->enum('status', ['success', 'failed'])->nullable();
            $table->string('payment_method')->nullable();
            $table->string('payment_info');
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
