<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::table('isletmecilik_transactions', function (Blueprint $table) {
        $table->unsignedBigInteger('firm_id')->nullable()->after('id');
    });
}

public function down()
{
    Schema::table('isletmecilik_transactions', function (Blueprint $table) {
        $table->dropColumn('firm_id');
    });
}

};
