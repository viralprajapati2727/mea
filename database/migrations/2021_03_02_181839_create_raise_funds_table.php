<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRaiseFundsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('raise_funds', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->decimal('amount', 10, 2);
            $table->decimal('received_amount', 10, 2);
            $table->decimal('commission', 10, 2);
            $table->decimal('commission_rate', 5, 2)->comment('percentage');
            $table->decimal('currency');
            $table->longText('description');
            $table->integer('views')->default(0);
            $table->integer('donors')->default(0);
            $table->integer('status')->comment('0 = Pending,1 = active, 2 = reject')->nullable();
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
        Schema::dropIfExists('raise_funds');
    }
}
