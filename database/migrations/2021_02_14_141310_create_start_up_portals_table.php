<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStartUpPortalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('start_up_portals', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('user_id');
            $table->longText('description');
            $table->string('industry');
            $table->string('location');
            $table->string('team_members')->nullable();
            $table->string('stage_of_startup');
            $table->string('important_next_step');
            $table->string('other_important_next_step')->nullable();
            $table->string('web_link')->nullable();
            $table->string('fb_link')->nullable();
            $table->string('insta_link')->nullable();
            $table->string('tw_link')->nullable();
            $table->string('linkedin_link')->nullable();
            $table->string('tiktok_link')->nullable();
            $table->string('business_plan')->nullable();
            $table->string('financial')->nullable();
            $table->string('pitch_deck')->nullable();
            $table->integer('status')->comment('0 = Pending,1 = active, 2 = reject')->nullable();
            $table->integer('is_view')->default(0)->comment("0 = not allowed, 1 = allowed ");
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
        Schema::dropIfExists('start_up_portals');
    }
}
