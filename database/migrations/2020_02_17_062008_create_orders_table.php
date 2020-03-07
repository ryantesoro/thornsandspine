<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code')->nullable();
            $table->unsignedInteger('recipient_id')->nullable();
            $table->text('remarks')->nullable();
            $table->integer('total');
            $table->integer('loyalty_points')->default(0);
            $table->integer('status')->default(0);
            $table->unsignedInteger('shipping_fees_id');
            $table->string('payment_method');
            $table->string('tracking_number')->nullable();
            $table->date('delivery_date');
            $table->text('comment')->nullable();
            $table->datetime('expires_at')->nullable();
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
        Schema::dropIfExists('orders');
    }
}
