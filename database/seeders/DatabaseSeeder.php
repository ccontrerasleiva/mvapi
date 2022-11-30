<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         \App\Models\User::factory()->create();
         $user = \App\Models\User::find(1);
         $token = $user->createToken('dorel-token')->plainTextToken;
         echo "El token del usuario es: ".explode('|', $token)[1];
    }
}
