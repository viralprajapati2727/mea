<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScheduleAppointmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schedule_appointments', function (Blueprint $table) {
            $table->id();
            $table->integer("startup_id");
            $table->integer("user_id");
            $table->string("time")->nullable();
            $table->string("zone")->nullable();
            $table->string("date")->nullable();
            $table->text("purpose_of_meeting")->nullable();
            $table->integer("status")->comment("0:pending,1:accepted,2:rejected")->nullable();
            $table->string("reason")->comment("if admin deny meeting")->nullable();
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
        Schema::dropIfExists('schedule_appointments');
    }
}
