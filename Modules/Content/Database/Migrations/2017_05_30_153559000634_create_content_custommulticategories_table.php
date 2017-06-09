<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContentCustomMultiCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('content__custommulticategories', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
             $table->integer('custom_content_id')->unsigned();
            $table->integer('category_id')->unsigned(); 

            $table->foreign('custom_content_id')->references('id')->on('content__custom_contentstories')->onDelete('cascade');
             $table->foreign('category_id')->references('id')->on('content__categories')->onDelete('cascade');   
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
        Schema::dropIfExists('content__custommulticategories');
    }
}
