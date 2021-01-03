<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommunityCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('community_comments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('community_id');
            $table->bigInteger('parent_id');
            $table->text('comment');
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
        Schema::dropIfExists('community_comments');
    }
}
