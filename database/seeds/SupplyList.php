<?php

use Illuminate\Database\Seeder;

class SupplyList extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $schools = App\Models\School\School::get();

        foreach ($schools as $school) {
        	foreach ($school->levels as $level) {
        	    DB::table('supply_lists')->insert([
		        	'city' => $school->city,
		        	'school_id' => $school->id,
		        	'school_level_id' => $level->id,
	        	]);
        	}
        }

        $products = App\Models\Product\Product::all();

        // Populate the pivot table
        App\Models\SupplyList::all()->each(function ($supply) use ($products) {
            $supply->products()->attach(
                $products->random(rand(1, 10))->pluck('id')->toArray()
            );
        });
    }
}
