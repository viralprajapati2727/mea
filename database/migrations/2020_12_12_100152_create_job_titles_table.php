<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobTitlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_titles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title')->nullable()->default(NULL);
            $table->tinyInteger('status')->comment('0:inactive,1:active')->default('1');
            $table->bigInteger('created_by')->nullable()->default(NULL);
            $table->bigInteger('updated_by')->nullable()->default(NULL);
            $table->bigInteger('deleted_by')->nullable()->default(NULL);
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
        Schema::dropIfExists('job_titles');
    }
}
