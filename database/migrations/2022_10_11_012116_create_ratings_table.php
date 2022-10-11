<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRatingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ratings', function (Blueprint $table) {
            $table->id();
            $table->string('name_teknisi')->nullable();
            $table->string('phone_teknisi')->nullable();
            $table->string('email_teknisi')->nullable();
            $table->double('rate')->nullable();
            $table->text('komentar')->nullable();
            $table->text('photo_teknisi')->nullable();
            $table->double('nilai')->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->bigInteger('perbaikan_id')->nullable();
            $table->bigInteger('room_id')->nullable();
            $table->bigInteger('keluhan_id')->nullable();
            $table->string('tanggal')->nullable();
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
        Schema::dropIfExists('ratings');
    }
}
