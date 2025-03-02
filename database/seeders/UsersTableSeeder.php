<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $password = Hash::make('admin');
        $inputs = [
            ['name' => 'Super Admin',
                'email' => 'admin@gmail.com',
                'password' => $password,
                'email_verified_at' => \Carbon\Carbon::now(),
                'is_active' => 1,
                'is_system' => 1,
            ],
        ];

        foreach ($inputs as $input) {
            User::create($input);
        }
    }
}
