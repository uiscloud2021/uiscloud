<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->string('name',100);
            $table->string('filename',200);
            $table->string('url',200);
            $table->string('size',50);
            $table->string('type',50);
            $table->unsignedBigInteger('version');
            $table->unsignedBigInteger('id_user');
            $table->unsignedBigInteger('category_id');
            $table->string('versionamiento',10);
            $table->enum('bloqueado', [0, 1])->default(0);
            $table->string('user_block',200);
            $table->unsignedBigInteger('id_folder');
            $table->unsignedBigInteger('nivel');
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
        Schema::dropIfExists('files');
    }
}
