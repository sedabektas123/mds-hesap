<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('endustriyel_cari_accounts', function (Blueprint $table) {
            $table->string('firm_name')->nullable()->after('firm_id');
        });
    }
    
    public function down()
    {
        Schema::table('endustriyel_cari_accounts', function (Blueprint $table) {
            $table->dropColumn('firm_name');
        });
    }
    
};
