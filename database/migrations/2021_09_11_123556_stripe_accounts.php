<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class StripeAccounts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('stripe_accounts', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('stripe_id')->nullable();
            $table->string('account_status')->nullable();
            $table->string('details_submitted')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('account_holder_name')->nullable();
            $table->string('routing_number')->nullable();
            $table->string('last4')->nullable();
            $table->text('stripe_object')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stripe_accounts');
    }
}
