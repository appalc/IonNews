<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContentContentUserTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('content__contentuser_translations', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            // Your translatable fields

            $table->integer('contentuser_id')->unsigned();
            $table->string('locale')->index();
            $table->unique(['contentuser_id', 'locale']);
            $table->foreign('contentuser_id')->references('id')->on('content__contentusers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('content__contentuser_translations', function (Blueprint $table) {
            $table->dropForeign(['contentuser_id']);
        });
        Schema::dropIfExists('content__contentuser_translations');
    }
}
