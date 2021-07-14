<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();

            //only include user_id if you want to associate a user on your Users table with this Transaction
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');

            $table->string('phone');
            $table->bigInteger('amount');
            $table->text('currency');

            //a new transaction will be marked as null until payment is confirmed
            $table->text('status')->nullable();

            //the reference and description will also be recorded on your pesapal dashboard
            $table->string('reference');
            $table->string('description');

            //this tracking_id is necessary when sending you notifications forexample if a payment is PENDING or COMPLETED etc...
            $table->string('pesapal_transaction_tracking_id')->nullable();

            $table->string('pesapal_merchant_reference')->nullable();
            $table->string('pesapal_notification_type')->nullable();

            //many payment methods exist such as mpesa, tigopesa, visa, mastercard, american express etc...
            $table->text('payment_method')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
