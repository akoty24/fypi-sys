<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Admin::create([
            'name_en' => 'admin',
            'name_ar' => 'ادمن',
            'email' => 'admin@app.com',
            'password' => bcrypt('12345678'),
        ]);
    }
}
