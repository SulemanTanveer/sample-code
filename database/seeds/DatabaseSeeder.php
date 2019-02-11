<?php

use Illuminate\Database\Seeder;
//use Eloquent;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Eloquent::unguard();
        $path = storage_path().'/cities.sql';
        DB::unprepared(file_get_contents($path));
        // $this->call(CitiesTableSeeder::class);
//        $this->call(CategoryTableSeeder::class);
//        $this->call(ProductColorSeeder::class);
        $this->call(ProductSizeSeeder::class);
//        $this->call(SchoolTableSeeder::class);
//        $this->call(ProductTableSeeder::class);
        $this->call(RolesTableSeeder::class);
        $this->call(UserTableSeeder::class);
        $this->call(UserStatusSeeder::class);
//        $this->call(SupplyList::class);
        $this->call(OrderAndDeliveryStatus::class);

//        factory(App\Models\Order\OrderProduct::class, 10)->create();

    }
}
