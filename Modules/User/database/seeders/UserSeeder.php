<?php

namespace Modules\User\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Modules\User\Enums\UserTypeEnum;
use Modules\User\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 1; $i <= 5; $i++) {
            User::query()->firstOrCreate([
                'email' => 'user' . $i . '@example.com',
            ], [
                'name'     => 'User ' . $i,
                'type'     => UserTypeEnum::NORMAL,
                'password' => Hash::make(12345678),
            ]);
        }
    }
}
