<?php

namespace Database\Seeders;

use App\Models\Client;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    public function run(): void
    {
        // Creates 25 fake clients for the first user (you)
        for ($i = 1; $i <= 25; $i++) {
            Client::create([
                'user_id' => 2,
                'name'    => "Test Client $i",
                'email'   => "client$i@example.com",
                'phone'   => "+222123456$i",
                'address' => "Address $i, Nouakchott",
            ]);
        }
    }
}