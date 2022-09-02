<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = [
            'user' => [
                'name' => 'user1',
                'email' => 'user1@example.com',
                'password' => '12345678',
                'isAdmin' => false,
            ],
            'admin' => [
                'name' => 'admin',
                'email' => 'admin@example.com',
                'password' => '12345678',
                'isAdmin' => true,
            ],
        ];
        foreach ($user as $item) {
            User::create([
                'name' => $item['name'],
                'email' => $item['email'],
                'password' => Hash::make($item['password']),
                'isAdmin' => $item['isAdmin'],
            ]);
        }
    }
}
