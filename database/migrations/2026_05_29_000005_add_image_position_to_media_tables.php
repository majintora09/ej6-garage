<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('community_posts') && ! Schema::hasColumn('community_posts', 'image_position')) {
            $after = Schema::hasColumn('community_posts', 'image_path') ? 'image_path' : 'visibility';

            Schema::table('community_posts', function (Blueprint $table) use ($after) {
                $table->string('image_position', 16)->default('center')->after($after);
            });
        }

        if (Schema::hasTable('car_photos') && ! Schema::hasColumn('car_photos', 'image_position')) {
            $after = Schema::hasColumn('car_photos', 'visibility') ? 'visibility' : 'path';

            Schema::table('car_photos', function (Blueprint $table) use ($after) {
                $table->string('image_position', 16)->default('center')->after($after);
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('community_posts') && Schema::hasColumn('community_posts', 'image_position')) {
            Schema::table('community_posts', function (Blueprint $table) {
                $table->dropColumn('image_position');
            });
        }

        if (Schema::hasTable('car_photos') && Schema::hasColumn('car_photos', 'image_position')) {
            Schema::table('car_photos', function (Blueprint $table) {
                $table->dropColumn('image_position');
            });
        }
    }
};
