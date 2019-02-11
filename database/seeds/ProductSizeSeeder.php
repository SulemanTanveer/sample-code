<?php

use Illuminate\Database\Seeder;

class ProductSizeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('product_sizes')->insert([
            'name' => 'petit',
        ]);

        DB::table('product_sizes')->insert([
            'name' => 'moyen',
        ]);
        DB::table('product_sizes')->insert([
            'name' => 'grand',
        ]);
    }
}
