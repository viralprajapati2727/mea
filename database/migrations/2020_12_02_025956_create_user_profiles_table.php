<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->bigIncrements('id')->autoIncrement();
            $table->bigInteger('user_id');
            $table->date('dob')->nullable()->default(NULL);
            $table->string('phone',100)->nullable()->default(NULL);
            $table->string('web_link',255)->nullable()->default(NULL);
            $table->string('fb_link',255)->nullable()->default(NULL);
            $table->string('insta_link',255)->nullable()->default(NULL);
            $table->string('tw_link',255)->nullable()->default(NULL);
            $table->longText('description')->nullable()->default(NULL);
            $table->string('city')->nullable()->default(NULL);
            $table->text('address')->nullable()->default(NULL);
            $table->string('gender')->nullable()->default(NULL);
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
        Schema::dropIfExists('user_profiles');
    }
}