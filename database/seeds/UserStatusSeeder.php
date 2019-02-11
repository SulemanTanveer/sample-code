<?php

use Illuminate\Database\Seeder;

class UserStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('user_status')->insert([
            'name' => 'created',
        ]);

        DB::table('user_status')->insert([
            'name' => 'active',
        ]);

    }
}
