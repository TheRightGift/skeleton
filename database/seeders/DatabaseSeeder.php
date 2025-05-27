<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Wallet;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $user = User::create([
            'name' => 'Gozie Chukwu',
            'email' => 'goziechukwu@gmail.com',
            'phone' => '+2349012345678',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
        ]);

        Wallet::create([
            'user_id' => $user->id,
            'balance' => 10000.00,
            'tipping_url' => config('app.url') . '/t/' . \Illuminate\Support\Str::random(32),
        ]);
    }
}