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
        Schema::table('receive_payment', function (Blueprint $table) {
            $table->string('customer_name')->nullable();
            $table->bigInteger('transaction_id')->nullable();
            $table->decimal('credit_amount', 22, 4)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('receive_payment', function (Blueprint $table) {
            $table->dropColumn('customer_name');
        });
    }
};
