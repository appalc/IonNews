<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContentContentUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('content__contentusers', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('content_id')->unsigned();   
            $table->integer('user_id')->unsigned();                    
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('content_id')->references('id')->on('content__contents')->onDelete('cascade');
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
        Schema::dropIfExists('content__contentusers');
    }
}
