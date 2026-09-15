<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCheckoutProfileFieldsToUsersTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('checkout_first_name')->nullable();
            $table->string('checkout_last_name')->nullable();
            $table->string('checkout_phone')->nullable();
            $table->string('checkout_country')->nullable();
            $table->text('checkout_address1')->nullable();
            $table->text('checkout_address2')->nullable();
            $table->string('checkout_post_code')->nullable();
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'checkout_first_name',
                'checkout_last_name',
                'checkout_phone',
                'checkout_country',
                'checkout_address1',
                'checkout_address2',
                'checkout_post_code',
            ]);
        });
    }
}
