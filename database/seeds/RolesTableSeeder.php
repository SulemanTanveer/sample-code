<?php

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            'name' => 'ADMIN',
            'description' => 'admin'
        ]);

        DB::table('roles')->insert([
            'name' => 'LOGISTICMANAGER',
            'description' => 'logistic manager'
        ]);

        DB::table('roles')->insert([
            'name' => 'PARENT',
            'description' => 'parent'
        ]);
    }
}
