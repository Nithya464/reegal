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
        Schema::table('categories', function (Blueprint $table) {
            $table->integer('website_sequence_no')->nullable()->after('slug');
            $table->enum('show_on_website',['1','2'])->default(2)->comment('1:Yes , 2:No')->after('website_sequence_no');
            $table->enum('status',['1','2'])->default(1)->comment('1:Active , 2: Inactive')->after('show_on_website');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('category', function (Blueprint $table) {
            //
        });
    }
};
