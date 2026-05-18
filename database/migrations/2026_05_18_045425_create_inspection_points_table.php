<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inspection_points', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('category')->nullable();
            $table->string('status')->nullable();
            $table->string('priority')->nullable();
            $table->text('description')->nullable();
            $table->decimal('x', 8, 3);
            $table->decimal('y', 8, 3);
            $table->decimal('z', 8, 3);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inspection_points');
    }
};
