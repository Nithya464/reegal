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
        Schema::create('product_stocks', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('product_id');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->string('barcode')->nullable();
            $table->double('before_stock')->default(0)->nullable()->comment('(In piece)');
            $table->double('affected_stock')->default(0)->nullable()->comment('(In piece)');
            $table->double('defaul_affected_stock')->default(0)->nullable();
            $table->double('current_stock')->default(0)->nullable()->comment('(In piece)');
            $table->double('current_stock_in_defaul_unit')->default(0)->nullable();
            $table->text('remark')->nullable();
            $table->string('reference_id')->nullable();
            $table->integer('updated_by')->unsigned();
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');
            $table->integer('created_by')->unsigned();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('product_stocks');
    }
};
