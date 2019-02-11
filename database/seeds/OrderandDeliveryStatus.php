<?php

use Illuminate\Database\Seeder;

class OrderAndDeliveryStatus extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('order_statuses')->insert([
            'name' => 'pending',
            'color' => 'yellow',
        ]);
        DB::table('order_statuses')->insert([
            'name' => 'confirmed',
            'color' => 'blue',
        ]);
        DB::table('order_statuses')->insert([
            'name' => 'completed',
            'color' => 'green',
        ]);
        DB::table('order_statuses')->insert([
            'name' => 'cancelled',
            'color' => 'red',
        ]);
        DB::table('order_statuses')->insert([
            'name' => 'unpaid',
            'color' => 'purple',
        ]);

         DB::table('delivery_status')->insert([
            'name' => 'prepared',
            'color' => 'white',
        ]);
         DB::table('delivery_status')->insert([
            'name' => 'sent',
            'color' => 'blue',
        ]);
         DB::table('delivery_status')->insert([
            'name' => 'received',
            'color' => 'green',
        ]);

        factory(App\Models\Shipment\Shipment::class, 1)->create();
        factory(App\Models\TermsAndConditions::class, 1)->create();
    }
}
