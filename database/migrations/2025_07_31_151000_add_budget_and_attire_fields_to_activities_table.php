<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('activities', function (Blueprint $table) {
            $table->decimal('budget', 8, 2)->nullable()->after('location');
            $table->string('attire_color')->nullable()->after('budget');
            $table->string('attire_note')->nullable()->after('attire_color');
        });
    }

    public function down(): void
    {
        Schema::table('activities', function (Blueprint $table) {
            $table->dropColumn(['budget', 'attire_color', 'attire_note']);
        });
    }
};
