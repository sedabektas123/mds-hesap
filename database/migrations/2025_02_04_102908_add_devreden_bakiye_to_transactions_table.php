<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('endustriyel_transactions', function (Blueprint $table) {
            $table->decimal('devreden_bakiye', 15, 2)->default(0)->after('amount');
        });
    }
    
    public function down(): void
    {
        Schema::table('endustriyel_transactions', function (Blueprint $table) {
            //
        });
    }
};
