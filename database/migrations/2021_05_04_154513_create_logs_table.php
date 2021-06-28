<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logs', function (Blueprint $table) {
            $table->id();
            $table->string('directory',100);
            $table->string('name',200);
            $table->string('details',200);
            $table->string('filename',200);
            $table->string('url',200);
            $table->string('size',50);
            $table->string('type',50);
            $table->unsignedBigInteger('version');
            $table->string('user',100);
            $table->unsignedBigInteger('file_id');
            $table->timestamps();

            //$table->foreign('user_id')->references('id')->on('users');
            //$table->foreign('file_id')->references('id')->on('files');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('logs');
    }
}
