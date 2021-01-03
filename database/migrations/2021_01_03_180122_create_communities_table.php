<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommunitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('communities', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id');
            $table->string('title',100)->nullable();
            $table->integer('question_category_id');
            $table->longText('description')->nullable();
            $table->integer('votes')->nullable()->default(0);
            $table->integer('view_count')->nullable()->default(0);
            $table->tinyInteger('status')->nullable()->default(1)->comment('0 = Pending,1 = active, 2 = reject, 3 = closed');
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
        Schema::dropIfExists('communities');
    }
}
