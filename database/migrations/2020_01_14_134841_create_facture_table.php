<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFactureTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('facture', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('num')->nullable();
            $table->date('dateDepot')->nullable();
            $table->time('heureDepot')->nullable();
            $table->bigInteger('prestataireId')->nullable();
            $table->string('factureFichier')->nullable();
            $table->string('cheminFichier')->nullable();
            $table->integer('etatId')->nullable();
            $table->decimal('montant')->nullable();
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
        Schema::dropIfExists('facture');
    }
}
