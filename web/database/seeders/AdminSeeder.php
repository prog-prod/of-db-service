<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $superAdmin = Admin::factory()->create([
            'is_confirmed' => 1,
            'name' => 'Admin',
            'email' => 'polyvyanyy.andrii@gmail.com',
        ]);

        $superAdmin->assignRole('super-admin');

        $admin = Admin::factory()->create([
            'is_confirmed' => 0,
            'name' => 'Test admin',
            'email' => 'test-admin@gmail.com',
        ]);

        $admin->assignRole('admin');

    }
}
