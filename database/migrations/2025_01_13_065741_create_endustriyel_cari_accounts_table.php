<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEndustriyelCariAccountsTable extends Migration
{
    public function up()
    {
        Schema::create('endustriyel_cari_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('firm_id')->constrained('endustriyel_firms')->onDelete('cascade'); // Endüstriyel firma ilişkisi
            $table->decimal('tahsilat', 15, 2)->default(0);
            $table->decimal('odeme', 15, 2)->default(0);
            $table->decimal('bakiye', 15, 2)->default(0);
            $table->decimal('alacak', 15, 2)->default(0);
            $table->decimal('borc', 15, 2)->default(0);
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Kullanıcı ilişkisi
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('endustriyel_cari_accounts');
    }
}
