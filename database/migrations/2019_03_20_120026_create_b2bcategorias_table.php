<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateB2bcategoriasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('b2bcategorias', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('categoria');
            $table->integer('subcategoria1')->nullable();
            $table->string('texto');
            $table->string('accion');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('b2bcategorias');
    }
}
