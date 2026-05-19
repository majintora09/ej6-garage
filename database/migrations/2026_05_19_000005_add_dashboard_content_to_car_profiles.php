<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('car_profiles', function (Blueprint $table) {
            $table->text('known_issues')->nullable()->after('build_vibe');
            $table->text('future_plans')->nullable()->after('known_issues');
            $table->unsignedTinyInteger('restoration_progress')->nullable()->after('future_plans');
        });
    }

    public function down(): void
    {
        Schema::table('car_profiles', function (Blueprint $table) {
            $table->dropColumn(['known_issues', 'future_plans', 'restoration_progress']);
        });
    }
};
