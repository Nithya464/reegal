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
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->string('car_nick_name')->nullable();
            $table->string('years')->nullable();
            $table->dateTime('exp_date')->nullable();
            $table->string('make_by')->nullable();
            $table->string('model')->nullable();
            $table->string('vin_no')->nullable();
            $table->string('licence_plate')->nullable();
            $table->string('start_mileage')->nullable();
            $table->enum('load_order_type',['1','2'])->comment('1:First In Last Out , 2: First In First Out')->nullable();
            $table->text('description')->nullable();
            $table->enum('status',['1','2'])->comment('1:Active , 2:De-Active')->nullable();
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
        Schema::dropIfExists('cars');
    }
};
