<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('maintenances', function (Blueprint $table) {
            $table->string('category')->nullable()->after('title');
            $table->date('next_due_date')->nullable()->after('service_date');
            $table->integer('next_due_mileage')->nullable()->after('next_due_date');
        });
    }

    public function down(): void
    {
        Schema::table('maintenances', function (Blueprint $table) {
            $table->dropColumn([
                'category',
                'next_due_date',
                'next_due_mileage',
            ]);
        });
    }
};
