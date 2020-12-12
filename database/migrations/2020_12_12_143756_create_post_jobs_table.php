<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('post_jobs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id');
            $table->integer('job_title_id');
            $table->integer('job_type_id');
            $table->integer('job_unique_id');
            $table->integer('currency_id');
            $table->integer('min_salary')->nullable()->comment('per year');
            $table->integer('max_salary')->nullable()->comment('per year');
            $table->string('job_start_time',50)->nullable();
            $table->string('job_end_time',50)->nullable();
            $table->text('location')->nullable();
            $table->longText('description')->nullable();
            $table->string('required_experience',50)->nullable();
            $table->tinyInteger('job_status')->nullable()->default(0)->comment('0 = Pending,1 = active, 2 = reject, 3 = closed');
            $table->integer('job_count')->nullable()->default(0);
            $table->integer('created_by')->comment('userid')->nullable();
            $table->integer('updated_by')->comment('userid')->nullable();
            $table->integer('deleted_by')->comment('userid')->nullable();
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
        Schema::dropIfExists('post_jobs');
    }
}
