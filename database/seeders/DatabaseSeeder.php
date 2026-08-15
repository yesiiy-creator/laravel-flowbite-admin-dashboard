<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::upsert([
            ['name' => 'Administrator Stockify', 'email' => 'admin@stockify.test', 'role' => 'admin', 'password' => Hash::make('password')],
            ['name' => 'Manajer Gudang', 'email' => 'manager@stockify.test', 'role' => 'manajer_gudang', 'password' => Hash::make('password')],
            ['name' => 'Staff Gudang', 'email' => 'staff@stockify.test', 'role' => 'staff_gudang', 'password' => Hash::make('password')],
        ], ['email'], ['name', 'role', 'password']);
        $category = Category::firstOrCreate(['name' => 'Elektronik'], ['description' => 'Perangkat elektronik']);
        $supplier = Supplier::firstOrCreate(['name' => 'PT Sumber Makmur'], ['phone' => '021-5550101', 'email' => 'sales@sumber.test', 'address' => 'Jakarta']);
        Product::firstOrCreate(['sku' => 'STK-001'], ['name' => 'Keyboard Mekanik', 'category_id' => $category->id, 'supplier_id' => $supplier->id, 'buy_price' => 350000, 'sell_price' => 475000, 'stock' => 18, 'min_stock' => 5]);
    }
}
