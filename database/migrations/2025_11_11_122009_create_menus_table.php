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
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->integer('parent_id')->nullable();
            $table->string('route', 200)->default('#');
            $table->string('permission_class', 512)->nullable();
            $table->tinyInteger('status')->nullable();
            $table->string('icon', 100)->nullable();
            $table->string('options', 200)->nullable();
            $table->integer('admin_left_section')->default(0);
            $table->boolean('top')->default(0);
            $table->boolean('left')->default(1);
            $table->boolean('footer')->default(0);
            $table->integer('order_by')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
