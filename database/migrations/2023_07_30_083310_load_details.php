<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class LoadDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('load_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pbs_id')->references('id')->on('pbs_info')->onDelete('cascade');
            $table->foreignId('grid_id')->references('id')->on('grids')->onDelete('cascade');
            $table->timestamp('time', 0);
            $table->integer('grid_demand');
            $table->integer('grid_supply');
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
        Schema::dropIfExists('load_details');
    }
}
