<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\IsletmecilikTransaction;
use App\Models\CariHesap; // Cari Hesap modeliniz
use Carbon\Carbon;

class SyncCariHesapToTransactions extends Command
{
    protected $signature = 'sync:cari-hesap-transactions';

    protected $description = 'Cari hesaptaki verileri otomatik olarak transactions tablosuna ekler';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Cari hesap verilerini çekin
        $cariHesaplar = CariHesap::where('synced', false)->get();

        foreach ($cariHesaplar as $cariHesap) {
            // Transaction tablosuna ekleme
            IsletmecilikTransaction::create([
                'firm' => $cariHesap->firm,
                'date' => $cariHesap->date ?? Carbon::now(),
                'amount' => $cariHesap->amount,
                'description' => $cariHesap->description,
                'type' => $cariHesap->type,
                'user_id' => $cariHesap->user_id, // Kullanıcı ID'si eşleşmesi
            ]);

            // Cari hesap kaydını "senkronize edildi" olarak işaretleyin
            $cariHesap->update(['synced' => true]);
        }

        $this->info('Cari hesap verileri başarılı bir şekilde transactions tablosuna eklendi.');
    }
}
