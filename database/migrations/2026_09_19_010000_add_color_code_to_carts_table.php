<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddColorCodeToCartsTable extends Migration
{
    public function up()
    {
        DB::statement('ALTER TABLE `products` MODIFY `color_code` VARCHAR(255) NULL');

        Schema::table('carts', function (Blueprint $table) {
            $table->string('color_code', 7)->nullable()->after('product_id');
        });
    }

    public function down()
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->dropColumn('color_code');
        });
    }
}
