<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UserApi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('users_api', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nama');
            $table->string('username');
            $table->string('password');
            $table->datetime('last_login')->nullable();
            $table->string('last_ip')->nullable();
            $table->integer('id_perush');
            $table->foreign('id_perush')->references('id_perush')->on('s_perusahaan');
            $table->boolean('status')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users_api');
    }
}
