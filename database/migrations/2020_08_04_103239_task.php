<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Task extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('task', function (Blueprint $table) {
            $table->id();
            $table->string('item');
            $table->boolean('status');
            $table->string('create_user');
            $table->string('update_user');
            $table->string('description')->nullable();
            $table->string('tag')->nullable();
            $table->string('image')->nullable();
            $table->unsignedBigInteger('card_id');
            $table->foreign('card_id')->references('id')->on('card')->onDelete('cascade')->onUpdate('cascade');
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
        //
    }
}
