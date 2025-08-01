<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        /*
        |----------------------------------------------------------
        | 1. Make sure the “Sample User” exists
        |    – *Do not* hard-code the ID; let MySQL auto-increment.
        |----------------------------------------------------------
        */
        $userId = DB::table('users')
            ->where('email', 'sample@example.com')
            ->value('id');

        if (! $userId) {
            $userId = DB::table('users')->insertGetId([
                'name'       => 'Sample User',
                'email'      => 'sample@example.com',
                'password'   => bcrypt('password'),   // 👉 default login: password
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        /*
        |----------------------------------------------------------
        | 2. Seed two demo itineraries for that user
        |----------------------------------------------------------
        */
        DB::table('itineraries')->insert([
            [
                'user_id'    => $userId,
                'title'      => 'Weekend Getaway',
                'description'=> 'Short trip to the mountains',
                'start_date' => Carbon::today()->addDays(7),
                'end_date'   => Carbon::today()->addDays(9),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id'    => $userId,
                'title'      => 'Business Trip',
                'description'=> 'Conference in New York',
                'start_date' => Carbon::today()->addDays(14),
                'end_date'   => Carbon::today()->addDays(17),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('itineraries')
            ->whereIn('title', ['Weekend Getaway', 'Business Trip'])
            ->delete();

        DB::table('users')
            ->where('email', 'sample@example.com')
            ->delete();
    }
};
