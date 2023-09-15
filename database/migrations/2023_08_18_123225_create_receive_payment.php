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
        Schema::create('receive_payment', function (Blueprint $table) {
            $table->increments('id');
            $table->string('reference_id')->nullable();
            $table->bigInteger('customer_id')->nullable();
            $table->string('payment_id')->nullable();
            $table->decimal('due_amount', 22, 4)->default(0);
            $table->decimal('receive_amount', 22, 4)->default(0);
            $table->enum('payment_status', ['new', 'complete'])->default('new');
            $table->date('receive_date')->nullable();
            $table->string('payment_mode')->nullable();
            $table->string('remark')->nullable();       
            $table->bigInteger('created_by')->nullable();
            $table->tinyInteger('status')->default(1)->comment('0 => Deactive, 1 => Active');
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
        Schema::dropIfExists('receive_payment');
    }
};
