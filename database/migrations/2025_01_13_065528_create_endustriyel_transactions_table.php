<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEndustriyelTransactionsTable extends Migration
{
    public function up()
    {
        Schema::create('endustriyel_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('firm')->nullable();
            $table->date('date');   // Tarih
            $table->decimal('amount', 10, 2); // Tutar
            $table->string('description')->nullable(); // Açıklama
            $table->enum('type', ['gelir', 'gider']);  // Gelir veya gider
            $table->unsignedBigInteger('user_id')->nullable(); // Kullanıcı ID'si
            $table->timestamps(); // created_at ve updated_at sütunları

            // user_id için foreign key (isteğe bağlı)
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('endustriyel_transactions');
    }
}
