<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blogs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title',255)->nullable();
            $table->text('short_description',255);
            $table->longText('description',255);
            $table->string('src',255)->nullable();
            $table->tinyInteger('status')->default(1)->comment('0 = draft, 1 = publish');
            $table->integer('created_by')->nullable()->comment('userid');
            $table->integer('updated_by')->nullable()->comment('userid');
            $table->integer('deleted_by')->nullable()->comment('userid');
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
        Schema::dropIfExists('blogs');
    }
}
