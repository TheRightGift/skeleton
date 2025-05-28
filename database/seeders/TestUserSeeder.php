<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Support\Facades\Hash;

class TestUserSeeder extends Seeder
{
    public function run()
    {
        // Create a test user
        $user = User::create([
            'name' => 'John Waiter',
            'email' => 'john@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        // Create a wallet for the user
        $wallet = Wallet::create([
            'user_id' => $user->id,
            'balance' => 0,
            'tipping_url' => config('app.url') . '/t/' . \Illuminate\Support\Str::random(32),
        ]);

        $this->command->info('Test user created:');
        $this->command->info('Email: john@example.com');
        $this->command->info('Password: password');
        $this->command->info('Tipping URL: ' . $wallet->tipping_url);
    }
}
