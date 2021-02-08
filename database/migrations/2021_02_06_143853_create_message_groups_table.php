<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessageGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('message_groups', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('message_group_unique_id',200)->nullable();
            $table->bigInteger('created_by')->comment('user table')->nullable();
            $table->tinyInteger('status')->default(1)->comment('0:Active, 1:Close');
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
        Schema::dropIfExists('message_groups');
    }
}
