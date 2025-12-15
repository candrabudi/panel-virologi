<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserDetail;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::create([
            'username' => 'admin.virologi',
            'email' => 'admin@virologi.id',
            'password' => Hash::make('Admin@12345'),
            'role' => 'admin',
            'status' => 'active',
        ]);

        UserDetail::create([
            'user_id' => $admin->id,
            'full_name' => 'Administrator Virologi',
            'phone_number' => '628111111111',
            'avatar' => null,
        ]);

        $editor = User::create([
            'username' => 'editor.virologi',
            'email' => 'editor@virologi.id',
            'password' => Hash::make('Editor@12345'),
            'role' => 'editor',
            'status' => 'active',
        ]);

        UserDetail::create([
            'user_id' => $editor->id,
            'full_name' => 'Editor Virologi',
            'phone_number' => '628122222222',
            'avatar' => null,
        ]);

        $user = User::create([
            'username' => 'user.virologi',
            'email' => 'user@virologi.id',
            'password' => Hash::make('User@12345'),
            'role' => 'user',
            'status' => 'active',
        ]);

        UserDetail::create([
            'user_id' => $user->id,
            'full_name' => 'User Virologi',
            'phone_number' => '628133333333',
            'avatar' => null,
        ]);
    }
}
