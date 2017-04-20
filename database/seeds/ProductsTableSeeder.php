<?php

use Illuminate\Database\Seeder;
use turnip\Products;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('products')->delete();
        Products::insert([
            ['name' => '蘿蔔糕', 'price' => 80, 'price2' => 90, 'unit' => '條'],
            ['name' => '蛋餅皮', 'price' => 3, 'price2' => 3, 'unit' => '片']
        ]);
    }
}
