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
        Schema::create('product_unit_price', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('product_id')->nullable();
            $table->string('unit')->nullable();
            $table->bigInteger('qty')->default(0)->nullable();
            $table->double('cost_price')->default(0)->nullable();
            $table->double('wh_min_price')->default(0)->nullable();
            $table->double('min_retail_price')->default(0)->nullable();
            $table->double('base_price')->default(0)->nullable();
            $table->string('rack')->nullable();
            $table->string('section')->nullable();
            $table->string('row')->nullable();
            $table->string('box_no')->nullable();
            $table->enum('create',['1','2'])->default(2)->comment('1:yes,2:no');
            $table->enum('is_default',['1','2'])->default(2)->comment('1:yes,2:no');
            $table->enum('is_free',['1','2'])->default(2)->comment('1:yes,2:no');
            $table->enum('status',['1','2'])->default(2)->comment('1:active,2:de-active');
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
        Schema::dropIfExists('product_unit_price');
    }
};
