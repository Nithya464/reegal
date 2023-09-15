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
        Schema::table('contacts', function (Blueprint $table) {
            $table->string('customer_type')->nullable()->after('custom_field10');
            $table->enum('status',['1','2'])->comment("1:active,2:Inactive")->default(1)->after('customer_type');
            $table->unsignedBigInteger('price_level_id')->after('status')->nullable();
            $table->foreign('price_level_id')->references('id')->on('price_levels');
            $table->integer('sales_person_id')->after('price_level_id')->unsigned()->nullable();
            $table->foreign('sales_person_id')->references('id')->on('users');
            $table->string('tax_id')->nullable()->after('sales_person_id');
            $table->string('term')->nullable()->after('tax_id');
            $table->string('bussiness_name')->nullable()->after('term');
            $table->string('otp_licence')->nullable()->after('bussiness_name');
            $table->string('store_licence')->nullable()->after('otp_licence');
            $table->string('store_open_time')->nullable()->after('store_licence');
            $table->string('store_close_time')->nullable()->after('store_open_time');
            $table->float('customer_discount')->default(0)->after('store_close_time')->comment('In Per(%)');
            $table->text('remark')->nullable()->after('customer_discount');
            $table->string('shipping_address_1')->nullable()->after('remark');
            $table->string('shipping_address_2')->nullable()->after('shipping_address_1');
            $table->string('shipping_zipcode')->nullable()->after('shipping_address_2');
            $table->string('shipping_state')->nullable()->after('shipping_zipcode');
            $table->string('shipping_city')->nullable()->after('shipping_state');
            $table->string('shipping_lat')->nullable()->after('shipping_city');
            $table->string('shipping_long')->nullable()->after('shipping_lat');
            $table->string('contact_type')->nullable()->after('shipping_long');
            $table->string('contact_person')->nullable()->after('contact_type');
            $table->string('contact_email')->nullable()->after('contact_person');
            $table->string('contact_landline_1')->nullable()->after('contact_email');
            $table->string('contact_landline_2')->nullable()->after('contact_landline_1');
            $table->string('contact_mobile')->nullable()->after('contact_landline_2');
            $table->string('contact_fax_no')->nullable()->after('contact_mobile');
            $table->enum('contact_is_default',['1','2'])->default(2)->after('contact_fax_no');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contacts', function (Blueprint $table) {
            $table->dropColumn('customer_type');
            $table->dropColumn('status');
            $table->dropForeign(['price_level_id']);
            $table->dropColumn('price_level_id');
            $table->dropForeign(['sales_person_id']);
            $table->dropColumn('sales_person_id');
            $table->dropColumn('tax_id');
            $table->dropColumn('term');
            $table->dropColumn('bussiness_name');
            $table->dropColumn('otp_licence');
            $table->dropColumn('store_licence');
            $table->dropColumn('store_open_time');
            $table->dropColumn('store_close_time');
            $table->dropColumn('customer_discount');
            $table->dropColumn('remark');
            $table->dropColumn('shipping_address_1');
            $table->dropColumn('shipping_address_2');
            $table->dropColumn('shipping_zipcode');
            $table->dropColumn('shipping_state');
            $table->dropColumn('shipping_city');
            $table->dropColumn('shipping_lat');
            $table->dropColumn('shipping_long');
            $table->dropColumn('contact_type');
            $table->dropColumn('contact_person');
            $table->dropColumn('contact_email');
            $table->dropColumn('contact_landline_1');
            $table->dropColumn('contact_landline_2');
            $table->dropColumn('contact_mobile');
            $table->dropColumn('contact_fax_no');
            $table->dropColumn('contact_is_default');
        });
    }
};
