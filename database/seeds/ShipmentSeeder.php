<?php

use Illuminate\Database\Seeder;

class ShipmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $shipment = \App\Models\Shipment\Shipment::whereId(1)->first();
        if($shipment)
        {
            $shipment->cost = 0.00;
            $shipment->is_free = 1;
            $shipment->save();
            DB::table('shipments')->insert([
                'is_free' => 0,
                'cost' => 5.95,
                'delivery_status_id' => 1,
            ]);
        }
        else{
            DB::table('shipments')->insert([
                'is_free' => 1,
                'cost' => 0.00,
                'delivery_status_id' => 1,
            ]);
            DB::table('shipments')->insert([
                'is_free' => 0,
                'cost' => 5.95,
                'delivery_status_id' => 1,
            ]);
        }
    }
}
