<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Support\Str;

class TestUserSeeder extends Seeder
{
    public function run(): void
    {
        // Check if test user already exists
        $existingUser = User::where('email', 'john@example.com')->first();

        if (!$existingUser) {
            // Create test user
            $user = User::create([
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'password' => bcrypt('password123'),
                'email_verified_at' => now(),
            ]);

            // Create wallet for test user
            Wallet::create([
                'user_id' => $user->id,
                'balance' => 0,
                'tipping_url' => url('/t/' . Str::random(32)),
            ]);

            $this->command->info('Test user created successfully!');
        } else {
            // Ensure wallet exists for existing user
            if (!$existingUser->wallet) {
                Wallet::create([
                    'user_id' => $existingUser->id,
                    'balance' => 0,
                    'tipping_url' => url('/t/' . Str::random(32)),
                ]);
                $this->command->info('Wallet created for existing test user.');
            } else {
                $this->command->info('Test user and wallet already exist.');
            }
        }
    }
}
