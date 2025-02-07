<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeFirmIdNullableInEndustriyelCariAccounts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('endustriyel_cari_accounts', function (Blueprint $table) {
            $table->unsignedBigInteger('firm_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('endustriyel_cari_accounts', function (Blueprint $table) {
            $table->unsignedBigInteger('firm_id')->nullable(false)->change();
        });
    }
}
