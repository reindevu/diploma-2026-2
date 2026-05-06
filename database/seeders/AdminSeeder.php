<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $phone = env('ADMIN_PHONE', '+79990000000');

        User::updateOrCreate(
            ['phone' => $phone],
            [
                'name' => env('ADMIN_NAME', 'Администратор CoffeeDoo'),
                'email' => env('ADMIN_EMAIL', 'admin@coffeedoo.local'),
                'password' => env('ADMIN_PASSWORD', 'Admin123!'),
                'role' => 'admin',
            ]
        );
    }
}
