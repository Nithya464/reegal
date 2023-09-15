<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendors', function (Blueprint $table) {
            $table->increments('id');
            $table->string('vendor_id')->nullable();
            $table->string('vendor_name');
            $table->enum('status', ['1', '2'])->default(1)->comment("1:Active , 2:De-Active");
            $table->string('sub_type');
            $table->string('company_name');
            $table->string('contact_person');
            $table->string('designation')->nullable();
            $table->string('cell');
            $table->string('fax')->nullable();
            $table->string('email')->nullable();
            $table->string('office_1');
            $table->string('other_1')->nullable();
            $table->string('cc_email')->nullable();
            $table->string('office_2')->nullable();
            $table->string('other_2')->nullable();
            $table->string('website')->nullable();
            $table->string('notes')->nullable();
            $table->string('address_1');
            $table->string('address_2')->nullable();
            $table->string('zip_code');
            $table->string('city');
            $table->string('state');
            $table->string('country');
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
        Schema::dropIfExists('vendors');
    }
};