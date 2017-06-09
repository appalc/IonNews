<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContentMultipleCategoryContentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('content__multiplecategorycontents', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('content_id')->unsigned();
            $table->integer('category_id')->unsigned(); 

            $table->foreign('content_id')->references('id')->on('content__contents')->onDelete('cascade');
             $table->foreign('category_id')->references('id')->on('content__categories')->onDelete('cascade');            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('content__multiplecategorycontents');
    }
}
