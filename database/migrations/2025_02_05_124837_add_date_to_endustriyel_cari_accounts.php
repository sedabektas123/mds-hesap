<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('endustriyel_cari_accounts', function (Blueprint $table) {
            $table->date('date')->after('firm_name')->nullable(); // 📌 `firm_name` sütunundan sonra ekledik!
        });
    }

    public function down()
    {
        Schema::table('endustriyel_cari_accounts', function (Blueprint $table) {
            $table->dropColumn('date'); // 📌 `date` sütununu geri kaldırır!
        });
    }
};
