<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKeluhansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('keluhans', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('room_id')->nullable();
            $table->string('tanggal')->nullable();
            $table->text('kendala')->nullable();
            $table->text('photo_kendala')->nullable();
            $table->enum('status', ['waiting', 'proses', 'selesai'])->default('waiting');
            $table->bigInteger('user_id')->nullable();
            $table->string('name_teknisi')->nullable();
            $table->text('token')->nullable();
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
        Schema::dropIfExists('keluhans');
    }
}
