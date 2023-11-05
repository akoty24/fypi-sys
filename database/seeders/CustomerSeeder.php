<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Customer::create([
            'name_en' => 'Customer',
            'name_ar' => 'عميل',
            'email' => 'customer@app.com',
            'password' => bcrypt('12345678'),
            'phone' => '1234567890',
            'country_code' => '+20',
            'status' => '0'
        ]);
    }
}
