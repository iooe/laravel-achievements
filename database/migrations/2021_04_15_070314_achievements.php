<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Achievements extends Migration
{

    public function up()
    {
        Schema::create('achievements', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('description')->nullable();
            $table->integer('group_id')->nullable();
            $table->smallInteger('level')->nullable();
            $table->integer('points');
            $table->integer('value')->default(0);
            $table->string('hash')->nullable();
            $table->index(['group_id', 'level']);
            $table->index('group_id');
        });
    }

    public function down()
    {
        //
    }
}
