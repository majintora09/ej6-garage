<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('build_timeline_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('car_profile_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('category')->nullable();
            $table->text('description')->nullable();
            $table->date('event_date')->nullable();
            $table->unsignedInteger('mileage')->nullable();
            $table->decimal('cost', 10, 2)->nullable();
            $table->string('image_path')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'car_profile_id', 'event_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('build_timeline_entries');
    }
};
