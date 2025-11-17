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
        Schema::table('volunteer_profiles', function (Blueprint $table) {
            $table->date('birth_date')->nullable()->after('address');
            $table->enum('gender', ['male', 'female'])->nullable()->after('birth_date');
            $table->string('education')->nullable()->after('gender');
            $table->string('institution')->nullable()->after('education');
            $table->text('experience')->nullable()->after('skills');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('volunteer_profiles', function (Blueprint $table) {
            $table->dropColumn(['birth_date', 'gender', 'education', 'institution', 'experience']);
        });
    }
};
