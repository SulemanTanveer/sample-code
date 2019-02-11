<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {


        DB::table('product_types')->insert([
            'type'=>'Listes préconçues' //Bundle
            ]);

        DB::table('product_status')->insert([
            'name'=>'active'
            ]);

        DB::table('users')->insert([
                    // 'name' => $faker->name,
                    'email' => 'admin@btc.fr',
                    'password' => '$2y$10$POWhp274QPlSDtmrKJvuDu3iXo7XfA3II3C1X4VnPcIiP.0tMar8y', // admin1
                    'remember_token' => str_random(10),
                    'status'    =>  2
                ]);
        $users = App\User::all();
        $faker = Faker::create();
        
        foreach ($users as $user):
            DB::table('user_profiles')->insert([
                'firstname' => $faker->name,
                'surname' => $faker->name,
                'user_id' => $user->id,
                'picture' => $faker->imageUrl(),
                'sexe' => $faker->title(),
                'birthdate' => $faker->dateTime(),
                'phone' => $faker->phoneNumber,
                'mobile' => $faker->phoneNumber,
                'description' => $faker->paragraph,
            ]);
            DB::table('user_addresses')->insert([
                'name' => $faker->streetName,
                'user_id' => $user->id,
                'city_id' => 1,
                'street_1' => $faker->streetAddress,
                'zip' => $faker->postcode
            ]);
        endforeach;

        
	    $role = App\Models\Role\Role::all();

        // Populate the pivot table
		App\User::all()->each(function ($user) use ($role) {
		    $user->roles()->attach(
		        $role->pluck('id')->toArray()
		    );
		});

    }
}
