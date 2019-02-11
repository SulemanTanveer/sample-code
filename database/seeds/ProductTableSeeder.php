<?php

use Illuminate\Database\Seeder;

class ProductTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $color = App\Models\Product\ProductColor::all();
        $size = App\Models\Product\ProductSize::all();
        $category = App\Models\Category\Category::all();

        factory(App\Models\Product\Product::class, 10)->create();
        factory(App\Models\Promotion\PromotionCode::class, 10)->create();

		// Populate the pivot table
		
		App\Models\product\Product::all()->each(function ($product) use ($category) { 
		    $product->categories()->attach(
		        $category->random(rand(1, 3))->pluck('id')->toArray()
		    );
		});
		
		App\Models\product\Product::all()->each(function ($product) use ($color) { 
		    $product->colors()->attach(
		        $color->random(rand(1, 3))->pluck('id')->toArray()
		    );
		});

		App\Models\product\Product::all()->each(function ($product) use ($size) { 
		    $product->sizes()->attach(
		        $size->random(rand(1, 3))->pluck('id')->toArray()
		    );
		});
        
    }
}
