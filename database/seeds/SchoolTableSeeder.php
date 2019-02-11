<?php

use Illuminate\Database\Seeder;

class SchoolTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\School\School::class, 5)->create();
        factory(App\Models\Level\Level::class, 5)->create();
	    
	    $level = App\Models\Level\Level::all();

		// Populate the pivot table
		App\Models\School\School::all()->each(function ($school) use ($level) { 
		    $school->levels()->attach(
		        $level->random(rand(1, 2))->pluck('id')->toArray()
		    );
		});
    }
}
