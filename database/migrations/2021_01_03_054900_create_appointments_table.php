<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppointmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id');
            $table->string('name',50)->nullable();
            $table->string('email',100)->nullable();
            $table->date('appointment_date')->nullable()->default(NULL);
            $table->string('time',50)->nullable();
            $table->longText('description')->nullable();
            $table->tinyInteger('status')->nullable()->default(0)->comment('0 = Pending,1 = active, 2 = reject');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('appointments');
    }
}
