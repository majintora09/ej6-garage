<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('active_car_profile_id')->nullable()->after('remember_token')->constrained('car_profiles')->nullOnDelete();
        });

        Schema::table('car_profiles', function (Blueprint $table) {
            $table->string('secondary_theme_color')->nullable()->after('theme_color');
            $table->string('visibility')->default('private')->after('model_path');
            $table->string('slug')->nullable()->after('visibility');
            $table->index(['user_id', 'slug']);
        });

        Schema::table('maintenances', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('id')->constrained()->cascadeOnDelete();
            $table->foreignId('car_profile_id')->nullable()->after('user_id')->constrained()->cascadeOnDelete();
            $table->index(['user_id', 'car_profile_id']);
        });

        Schema::table('mods', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('id')->constrained()->cascadeOnDelete();
            $table->foreignId('car_profile_id')->nullable()->after('user_id')->constrained()->cascadeOnDelete();
            $table->index(['user_id', 'car_profile_id']);
        });

        Schema::table('car_photos', function (Blueprint $table) {
            $table->string('category')->default('exterior')->after('original_name');
            $table->text('notes')->nullable()->after('caption');
            $table->string('visibility')->default('private')->after('notes');
        });

        $profiles = DB::table('car_profiles')->orderBy('id')->get();

        foreach ($profiles as $profile) {
            $baseSlug = Str::slug(trim(($profile->year ? $profile->year.' ' : '').($profile->make ?? '').' '.($profile->model ?? ''))) ?: 'garage-car';
            $slug = $baseSlug;
            $suffix = 2;

            while (DB::table('car_profiles')
                ->where('user_id', $profile->user_id)
                ->where('slug', $slug)
                ->where('id', '!=', $profile->id)
                ->exists()) {
                $slug = $baseSlug.'-'.$suffix++;
            }

            DB::table('car_profiles')->where('id', $profile->id)->update([
                'slug' => $slug,
                'visibility' => 'private',
                'secondary_theme_color' => $profile->secondary_theme_color ?? null,
            ]);
        }

        $users = DB::table('users')->get();

        foreach ($users as $user) {
            $firstCarId = DB::table('car_profiles')
                ->where('user_id', $user->id)
                ->orderBy('id')
                ->value('id');

            if (! $firstCarId) {
                continue;
            }

            DB::table('users')->where('id', $user->id)->update([
                'active_car_profile_id' => $firstCarId,
            ]);

            DB::table('maintenances')
                ->whereNull('user_id')
                ->update([
                    'user_id' => $user->id,
                    'car_profile_id' => $firstCarId,
                ]);

            DB::table('mods')
                ->whereNull('user_id')
                ->update([
                    'user_id' => $user->id,
                    'car_profile_id' => $firstCarId,
                ]);
        }
    }

    public function down(): void
    {
        Schema::table('car_photos', function (Blueprint $table) {
            $table->dropColumn(['category', 'notes', 'visibility']);
        });

        Schema::table('mods', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['car_profile_id']);
            $table->dropIndex(['user_id', 'car_profile_id']);
            $table->dropColumn(['user_id', 'car_profile_id']);
        });

        Schema::table('maintenances', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['car_profile_id']);
            $table->dropIndex(['user_id', 'car_profile_id']);
            $table->dropColumn(['user_id', 'car_profile_id']);
        });

        Schema::table('car_profiles', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'slug']);
            $table->dropColumn(['secondary_theme_color', 'visibility', 'slug']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['active_car_profile_id']);
            $table->dropColumn('active_car_profile_id');
        });
    }
};
