<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->tinyInteger('type')->default(2)->comment('1 = Admin, 2 = simple-user, 3 = entrepreneur, 4 = staff');
            $table->tinyInteger('is_register_with_platform')->default(1)->comment('1:Yes, 0:No');
            $table->string('provider_id')->nullable();
            $table->string('provider_name')->nullable();
            $table->tinyInteger('is_active')->default(0)->comment('0 = pending, 1 = active, 2 = deactive');
            $table->string('logo')->nullable();
            $table->tinyInteger('is_profile_filled')->default(0)->comment('1:Yes, 0:No');
            $table->string('ip_address', 15)->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
