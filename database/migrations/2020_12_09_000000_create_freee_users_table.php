<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFreeeUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('freee_users', function (Blueprint $table) {
            $table->bigInteger('id')->unique();
            $table->foreign('id')->references('id')->on('users');
            $table->string('freee_user_id')->nullable();
            $table->string('freee_token')->nullable();
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
        Schema::dropIfExists('freee_users');
    }
}
