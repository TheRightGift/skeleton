<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Wallet;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Call the TestUserSeeder to create a test user and wallet
        $this->call(TestUserSeeder::class);

        // You can add more seeders here if needed
        // $this->call(AnotherSeeder::class);
    }
}
