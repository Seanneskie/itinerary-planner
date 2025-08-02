<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('itineraries', function (Blueprint $table) {
            $table->string('photo_path')->nullable()->after('end_date');
        });

        Schema::table('group_members', function (Blueprint $table) {
            $table->string('photo_path')->nullable()->after('notes');
        });
    }

    public function down(): void
    {
        Schema::table('group_members', function (Blueprint $table) {
            $table->dropColumn('photo_path');
        });

        Schema::table('itineraries', function (Blueprint $table) {
            $table->dropColumn('photo_path');
        });
    }
};
