<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class HourlyLoadUpdatesMailTracker extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hourly_load_updates_mail_tracker', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pbs_id')->references('id')->on('pbs_info')->onDelete('cascade');
            $table->string('time');
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
        Schema::dropIfExists('hourly_load_updates_mail_tracker');
    }
}
