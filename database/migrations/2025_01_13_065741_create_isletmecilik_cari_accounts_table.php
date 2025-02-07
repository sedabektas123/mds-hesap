<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIsletmecilikCariAccountsTable extends Migration
{
    public function up()
    {
        Schema::create('isletmecilik_cari_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('firm_id')->constrained('isletmecilik_firms')->onDelete('cascade');
            $table->decimal('tahsilat', 15, 2)->default(0);
            $table->decimal('odeme', 15, 2)->default(0);
            $table->decimal('bakiye', 15, 2)->default(0);
            $table->decimal('alacak', 15, 2)->default(0);
            $table->decimal('borc', 15, 2)->default(0);
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('isletmecilik_cari_accounts');
    }
    public function create()
{
    $firms = IsletmecilikFirm::all(); // Firmaları alın
    return view('isletmecilik_cari_accounts.create', compact('firms'));
}
}
