<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();

            $table->string('npm')->nullable();
            $table->string('tgl_lahir')->nullable();
            $table->string('gender')->nullable();
            $table->string('phone')->nullable();
            $table->enum('role', ['karyawan', 'kepala_ruangan', 'teknisi', 'direktur', 'kepala_pde'])->default('karyawan');
            $table->text('alamat')->nullable();
            $table->text('avatar')->nullable();
            $table->enum('status', ['menunggu', 'konfirmasi'])->default('menunggu');

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
        Schema::dropIfExists('users');
    }
}
