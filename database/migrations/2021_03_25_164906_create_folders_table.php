<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFoldersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('folders', function (Blueprint $table) {
            $table->id();
            $table->string('name',100);
            $table->string('url',200);
            $table->enum('contenido', [0, 1])->default(0);
            $table->unsignedBigInteger('nivel');
            $table->unsignedBigInteger('id_user');
            $table->unsignedBigInteger('category_id');
            $table->timestamps();

            $table->foreign('id_user')->references('id')->on('users');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('folders');
    }
}
