<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWebMenuB2b extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('web_menu_b2b', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('categoria');
            $table->integer('subcategoria1');
            $table->integer('subcategoria2');
            $table->string('texto');
            $table->string('accion');
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
        Schema::dropIfExists('web_menu_b2b');
    }
}
