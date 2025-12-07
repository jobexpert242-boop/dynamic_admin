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
        Schema::create('billings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->string('address')->nullable();
            $table->string('invoice')->nullable()->unique();
            $table->date('invoice_date');
            $table->decimal('total', 12, 2);
            $table->decimal('discount', 8, 2)->default(0.00);
            $table->string('discount_type', 250)->nullable();
            $table->enum('status', ['due', 'unpaid', 'paid', 'cancelled'])->default('due');
            $table->string('paid', 250)->nullable();
            $table->string('token', 250)->nullable();
            $table->string('sub_total', 255)->nullable();
            $table->string('total_discount', 250)->nullable();
            $table->string('total_tax', 250)->nullable();
            $table->string('round_value', 250)->default('0.00');
            $table->text('terms')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('billings');
    }
};
