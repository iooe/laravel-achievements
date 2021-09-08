<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AchievementsProgress extends Migration
{

    public function up()
    {
        Schema::create('achievements_progress', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('achievement_id');
            $table->integer('points')->default(0);
            $table->dateTime('unlocked_at')->nullable();
            $table->timestamps();
            $table->unique(['user_id', 'achievement_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('achievements_progress');
    }
}
