<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NewUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = \App\Models\User::factory()->create([
            'name' => 'کاربر1',
            'email' => 'user@gmail.com',
            'mobile' => '+989111111111',
            'password' => bcrypt('password')
        ]);
        $user->save();
        $this->command->info('کاربر با موفقیت ایجاد شد');
    }
}
