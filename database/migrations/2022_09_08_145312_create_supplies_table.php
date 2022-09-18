<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuppliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supplies', function (Blueprint $table) {
            $table->id();
            $table->string('name_toko')->nullable();
            $table->string('phone')->nullable();
            $table->text('alamat')->nullable();
            $table->string('name_barang')->nullable();
            $table->text('photo_product')->nullable();
            $table->integer('quantity')->nullable();
            $table->integer('harga')->nullable();
            $table->integer('total_harga')->nullable();
            $table->text('photo_barcode')->nullable();
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
        Schema::dropIfExists('supplies');
    }
}
