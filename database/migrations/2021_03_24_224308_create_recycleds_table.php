<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecycledsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recycleds', function (Blueprint $table) {
            $table->id();
            $table->string('name',100);
            $table->string('filename',200);
            $table->string('url',200);
            $table->string('url_new',200);
            $table->string('size',50);
            $table->string('type',50);
            $table->unsignedBigInteger('version');
            $table->string('user',100);
            $table->string('category',100);
            $table->string('folder',100);
            $table->unsignedBigInteger('file_id');
            
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
        Schema::dropIfExists('recycleds');
    }
}
