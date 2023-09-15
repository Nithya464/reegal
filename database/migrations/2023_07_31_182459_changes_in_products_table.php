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
        Schema::table('products', function (Blueprint $table) {
            $table->integer('contact_id')->after('not_for_selling')->unsigned()->nullable();
            $table->foreign('contact_id')->references('id')->on('contacts');
            $table->string('re_order_mark')->nullable()->after('contact_id');
            $table->enum('is_ml_qty',['1','2'])->default(2)->after('re_order_mark')->comment('1:yes , 2:no');
            $table->enum('is_weight',['1','2'])->default(2)->after('is_ml_qty')->comment('1:yes , 2:no');
            $table->float('ml_qty')->default(0)->after('is_weight');
            $table->float('commission_per')->default(0)->after('ml_qty')->comment('In Per(%)');
            $table->float('stock')->default(0)->after('commission_per')->comment('In Pieces');
            $table->double('srp')->default(0)->after('stock')->comment('Suggested Retail Price');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['contact_id']);
            $table->dropColumn('contact_id');
            $table->dropColumn('re_order_mark');
            $table->dropColumn('is_ml_qty');
            $table->dropColumn('is_weight');
            $table->dropColumn('ml_qty');
            $table->dropColumn('commission_per');
            $table->dropColumn('stock');
            $table->dropColumn('srp');
        });
    }
};
