<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContentContentCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('content__contentcompanies', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
             $table->integer('content_id')->unsigned();
            $table->string('company_name')->nullable();
            $table->timestamps();
            $table->foreign('content_id')->references('id')->on('content__contents')->onDelete('cascade');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('content__contentcompanies');
    }
}
