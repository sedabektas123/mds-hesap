<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('isletmecilik_cari_accounts', function (Blueprint $table) {
            $table->date('islem_tarihi')->after('firm_id')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('isletmecilik_cari_accounts', function (Blueprint $table) {
            $table->dropColumn('islem_tarihi');
        });
    }
    
};
