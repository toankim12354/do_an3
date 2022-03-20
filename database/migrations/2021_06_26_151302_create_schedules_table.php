<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger("id_assign");
            $table->unsignedInteger("id_class_room");
            $table->unsignedInteger("id_lesson");
            $table->tinyInteger("day");
            $table->dateTime("day_finish")->nullable();
            $table->timestamps();
            $table->foreign("id_assign")->references("id")->on("assigns");
            $table->foreign("id_class_room")->references("id")->on("class_rooms");
            $table->foreign("id_lesson")->references("id")->on("lessons");
            $table->unique(["day", "id_class_room", "id_lesson", "day_finish"]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('schedules');
    }
}
