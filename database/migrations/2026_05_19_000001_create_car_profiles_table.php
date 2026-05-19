<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('car_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('make')->nullable();
            $table->string('model')->nullable();
            $table->string('chassis')->nullable();
            $table->unsignedSmallInteger('year')->nullable();
            $table->string('engine')->nullable();
            $table->string('color_name')->nullable();
            $table->string('color_code')->nullable();
            $table->string('theme_color')->nullable();
            $table->text('build_vibe')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('car_profiles');
    }
};
