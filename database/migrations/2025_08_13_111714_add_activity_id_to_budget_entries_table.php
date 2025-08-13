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
        if (! Schema::hasColumn('budget_entries', 'activity_id')) {
            Schema::table('budget_entries', function (Blueprint $table) {
                $table->foreignId('activity_id')->nullable()->constrained()->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('budget_entries', 'activity_id')) {
            Schema::table('budget_entries', function (Blueprint $table) {
                $table->dropForeign(['activity_id']);
                $table->dropColumn('activity_id');
            });
        }
    }
};
