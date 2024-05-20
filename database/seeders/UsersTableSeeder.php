<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->createAdminUser();
        $this->createUser();
    }

    private function createAdminUser()
    {
        $user = \App\Models\User::factory()->create([
            'type' => User::TYPE_ADMIN,
            'name' => 'ادمین',
            'email' => 'ahoseinmasumpoora@gmail.com',
            'mobile' => '+989374838311'
        ]);
        $user->save();
        $this->command->info('ادمین با موفقیت ایجاد شد');
    }

    private function createUser()
    {
        $user = \App\Models\User::factory()->create([
            'name' => 'کاربر1',
            'email' => 'user1@gmail.com',
            'mobile' => '+989111111111'
        ]);
        $user->save();
        $this->command->info('کاربر با موفقیت ایجاد شد');
    }
}
