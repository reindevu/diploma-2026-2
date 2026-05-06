<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class MakeAdminCommand extends Command
{
    protected $signature = 'app:make-admin';

    protected $description = 'Create or update CoffeeDoo admin user from environment variables';

    public function handle(): int
    {
        $phone = env('ADMIN_PHONE');
        $password = env('ADMIN_PASSWORD');

        if (!$phone || !$password) {
            $this->error('ADMIN_PHONE and ADMIN_PASSWORD are required.');
            return self::FAILURE;
        }

        $admin = User::updateOrCreate(
            ['phone' => $phone],
            [
                'name' => env('ADMIN_NAME', 'Администратор CoffeeDoo'),
                'email' => env('ADMIN_EMAIL'),
                'password' => $password,
                'role' => 'admin',
            ]
        );

        $this->info("Admin is ready: {$admin->phone}");

        return self::SUCCESS;
    }
}
