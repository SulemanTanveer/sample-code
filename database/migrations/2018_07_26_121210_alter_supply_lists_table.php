<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterSupplyListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('supply_lists', function (Blueprint $table) {

            $table->string('pdf_url')->nullable()->after('school_level_id');
            $table->integer('no_of_products_req')->default(0)->after('pdf_url');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('supply_lists', function (Blueprint $table) {
            //
        });
    }
}
