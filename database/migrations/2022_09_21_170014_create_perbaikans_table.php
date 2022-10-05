<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePerbaikansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('perbaikans', function (Blueprint $table) {
            $table->id();
            $table->text('perbaikan')->nullable();
            $table->string('tanggal')->nullable();
            $table->text('photo_perbaikan')->nullable();
            $table->bigInteger('supply_id')->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->bigInteger('keluhan_id')->nullable();
            $table->double('rating')->nullable();
            $table->text('komentar')->nullable();
            $table->integer('quantity')->nullable();
            $table->integer('total')->nullable();
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
        Schema::dropIfExists('perbaikans');
    }
}
