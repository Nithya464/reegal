<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->increments('id');
            $table->date('date');
            $table->string('payment_method')->nullable();
            $table->integer('payment_source_id')->nullable();
            $table->integer('expense_category_id')->unsigned()->nullable();
            $table->foreign('expense_category_id')->references('id')->on('expense_categories');
            $table->double('expense_amount')->unsigned()->nullable()->default('0.00');
            $table->string('description');
            $table->double('bill_$100')->unsigned()->nullable();
            $table->double('bill_$50')->unsigned()->nullable();
            $table->double('bill_$20')->unsigned()->nullable();
            $table->double('bill_$10')->unsigned()->nullable();
            $table->double('bill_$5')->unsigned()->nullable();
            $table->double('bill_$2')->unsigned()->nullable();
            $table->double('bill_$1')->unsigned()->nullable();
            $table->double('bill_50¢')->unsigned()->nullable();
            $table->double('bill_25¢')->unsigned()->nullable();
            $table->double('bill_10¢')->unsigned()->nullable();
            $table->double('bill_5¢')->unsigned()->nullable();
            $table->double('bill_1¢')->unsigned()->nullable();
            $table->integer('created_by')->unsigned();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->integer('deleted_by')->unsigned()->nullable();
            $table->foreign('deleted_by')->references('id')->on('users')->onDelete('set null');
            $table->softDeletes();
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
        Schema::dropIfExists('expenses');
    }
};
