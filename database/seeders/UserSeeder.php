<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Store;
use App\Models\Branch;
use App\Models\Category;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a user
        $user = User::create([
            'name_en' => 'user',
            'name_ar' => 'مستخدم',
            'email' => 'user@app.com',
            'password' => bcrypt('12345678'),
            'status' => '0',
        ]);

//        // Create a store for the user
//        $store = $user->stores()->create([
//            'name' => 'My Store',
//            // Add other store fields
//        ]);
//
//        // Create a branch for the store
//        $branch = $store->branches()->create([
//            'name' => 'Main Branch',
//            // Add other branch fields
//        ]);
//
//        // Create categories for the branch
//        $category1 = $branch->categories()->create([
//            'name' => 'Category 1',
//            // Add other category fields
//        ]);
//        $category2 = $branch->categories()->create([
//            'name' => 'Category 2',
//            // Add other category fields
//        ]);
//
//        // Create products for each category
//        $product1 = $category1->products()->create([
//            'name' => 'Product 1',
//            // Add other product fields
//        ]);
//        $product2 = $category2->products()->create([
//            'name' => 'Product 2',
//            // Add other product fields
//        ]);
//
////        // Create orders for the user
////        $order1 = $user->orders()->create([
////            // Add order details
////        ]);
////        $order2 = $user->orders()->create([
////            // Add order details
////        ]);
//
////        // Attach products to orders if needed
////        $order1->products()->attach([$product1->id, $product2->id]);
////        $order2->products()->attach([$product2->id]);
//
//        // You can continue to create more data as needed
//
//        // ...
    }
}
