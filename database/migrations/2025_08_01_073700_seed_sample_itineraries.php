<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

return new class extends Migration {
    public function up(): void
    {
        // ensure a sample user exists
        DB::table('users')->insert([
            'id' => 1,
            'name' => 'Sample User',
            'email' => 'sample@example.com',
            'password' => bcrypt('password'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('itineraries')->insert([
            [
                'user_id' => 1,
                'title' => 'Weekend Getaway',
                'description' => 'Short trip to the mountains',
                'start_date' => Carbon::today()->addDays(7),
                'end_date' => Carbon::today()->addDays(9),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 1,
                'title' => 'Business Trip',
                'description' => 'Conference in New York',
                'start_date' => Carbon::today()->addDays(14),
                'end_date' => Carbon::today()->addDays(17),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down(): void
    {
        DB::table('itineraries')->whereIn('title', ['Weekend Getaway', 'Business Trip'])->delete();
        DB::table('users')->where('email', 'sample@example.com')->delete();
    }
};
