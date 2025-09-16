<?php

namespace Database\Seeders;

use App\Models\OrderType;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::firstOrCreate(
            ['email' => 'cyrusmanatad227@gmail.com'],
            [
                'name' => 'Cyrus',
                'password' => Hash::make('P@55w0rd'),
                'email_verified_at' => now(),
            ]
        );

        OrderType::upsert([
            [
                "ot_code" => "SL", 
                "title" => "Sales Order",
                "created_at" => now()
            ],
            [
                "ot_code" => "PS", 
                "title" => "Promo Samples", 
                "created_at" => now()
            ]
        ], ["ot_code"]);
    }
}
