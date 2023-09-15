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
        Schema::create('write_cheques', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('cheque_number');
            $table->integer('employee_id')->nullable()->unsigned();
            $table->integer('vendor_id')->nullable()->unsigned();
            $table->date('date');
            $table->integer('cheque_amount');
            $table->text('address')->nullable();
            $table->text('memo')->nullable();
            $table->integer('created_by')->unsigned();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('employee_id')->references('id')->on('users');
            $table->foreign('vendor_id')->references('id')->on('vendors');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('write_cheques');
    }
};
