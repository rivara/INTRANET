<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateB2bsubcategoriasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('b2bsubcategorias', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('subcategoria1');
            $table->integer('subcategoria2');
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
        Schema::dropIfExists('b2bsubcategorias');
    }
}
