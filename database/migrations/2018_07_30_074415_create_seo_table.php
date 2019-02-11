<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSeoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seo', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('n')->nullable();
            $table->string('code_etablissement')->nullable();
            $table->string('url')->nullable();
            $table->string('ecole')->nullable();
            $table->string('slug')->nullable();
            $table->string('denomination_principale')->nullable();
            $table->string('patronyme_uai')->nullable();
            $table->string('statut')->nullable();
            $table->string('adresse')->nullable();
            $table->string('lieu_dit')->nullable();
            $table->string('code_postal')->nullable();
            $table->string('ville')->nullable();
            $table->string('coordonnee_X')->nullable();
            $table->string('coordonnee_Y')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->string('localisation')->nullable();
            $table->string('code_nature_uai')->nullable();
            $table->string('nature_uai')->nullable();
            $table->string('cp')->nullable();
            $table->string('code_region')->nullable();
            $table->string('code_academie')->nullable();
            $table->string('mail')->nullable();
            $table->string('academie')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('seos');
    }
}
