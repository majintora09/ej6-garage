<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('car_profiles', function (Blueprint $table) {
            $table->string('body_type')->default('coupe')->after('interior');
            $table->string('model_path')->nullable()->after('body_type');
        });

        Schema::table('inspection_points', function (Blueprint $table) {
            $table->foreignId('car_profile_id')->nullable()->after('id')->constrained()->nullOnDelete();
            $table->decimal('normalized_x', 8, 5)->nullable()->after('z');
            $table->decimal('normalized_y', 8, 5)->nullable()->after('normalized_x');
            $table->decimal('normalized_z', 8, 5)->nullable()->after('normalized_y');
        });
    }

    public function down(): void
    {
        Schema::table('inspection_points', function (Blueprint $table) {
            $table->dropForeign(['car_profile_id']);
            $table->dropColumn(['car_profile_id', 'normalized_x', 'normalized_y', 'normalized_z']);
        });

        Schema::table('car_profiles', function (Blueprint $table) {
            $table->dropColumn(['body_type', 'model_path']);
        });
    }
};
