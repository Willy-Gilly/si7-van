<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFluxTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('flux', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('titre')->nullable();
            $table->date('date')->nullable();
            $table->double('montant')->nullable();
            $table->bigInteger('comptableId')->nullable();
            $table->bigInteger('prestataireId')->nullable();
            $table->boolean('envoye')->nullable();
            $table->date('dateEnvoi')->nullable();
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
        Schema::dropIfExists('flux');
    }
}
