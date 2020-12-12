<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKeySkillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('key_skills', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->integer('created_by')->nullable()->comment('userid');
            $table->tinyInteger('status')->default(1)->comment('0 = disable, 1 = enable');
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
        Schema::dropIfExists('key_skills');
    }
}
