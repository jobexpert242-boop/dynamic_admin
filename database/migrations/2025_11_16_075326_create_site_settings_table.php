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
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('logo')->nullable();
            $table->string('favaicon')->nullable();
            $table->text('docs')->nullable();
            $table->text('inv_termes')->nullable();
            $table->string('tax')->nullable();
            $table->string('inv_prefix')->nullable();
            $table->string('company_location')->nullable();
            $table->string('company_contact')->nullable();
            $table->string('company_email')->nullable();
            $table->string('company_web')->nullable();
            $table->string('company_facebook')->nullable();
            $table->string('company_youtube')->nullable();
            $table->string('company_linkdin')->nullable();
            $table->string('site_termes')->nullable();
            $table->string('site_about')->nullable();
            $table->enum('currency', ['BDT', 'USD'])->default('BDT');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_settings');
    }
};
