<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDescriptionToEndustriyelCariAccountsTable extends Migration
{
    public function up()
    {
        Schema::table('endustriyel_cari_accounts', function (Blueprint $table) {
            $table->string('description')->nullable()->after('borc'); // Borç sütunundan sonra eklenir
        });
    }

    public function down()
    {
        Schema::table('endustriyel_cari_accounts', function (Blueprint $table) {
            $table->dropColumn('description');
        });
    }
}
