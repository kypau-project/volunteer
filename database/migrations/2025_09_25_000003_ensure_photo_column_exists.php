<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // First check if foto column exists and photo doesn't
        if (Schema::hasColumn('users', 'foto') && !Schema::hasColumn('users', 'photo')) {
            Schema::table('users', function (Blueprint $table) {
                $table->renameColumn('foto', 'photo');
            });
        }
        // If both exist, merge data and remove foto
        else if (Schema::hasColumn('users', 'foto') && Schema::hasColumn('users', 'photo')) {
            Schema::table('users', function (Blueprint $table) {
                // Copy data from foto to photo where photo is null
                DB::statement('UPDATE users SET photo = foto WHERE photo IS NULL');
                $table->dropColumn('foto');
            });
        }
        // If neither exist, create photo
        else if (!Schema::hasColumn('users', 'foto') && !Schema::hasColumn('users', 'photo')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('photo')->nullable();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('users', 'photo')) {
            Schema::table('users', function (Blueprint $table) {
                $table->renameColumn('photo', 'foto');
            });
        }
    }
};
