<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\EndustriyelCariAccount;
use App\Models\EndustriyelTransaction;

class SyncEndustriyelCariToTransactions extends Command
{
    protected $signature = 'sync:endustriyel-transactions';
    protected $description = 'Endüstriyel cari hesaplardan gelir/gider tablolarına veri aktarımı';

    public function handle()
    {
        $cariHesaplar = EndustriyelCariAccount::where('synced', false)->get();

        foreach ($cariHesaplar as $cari) {
            // Tahsilatı gelire ekle
            if ($cari->tahsilat > 0) {
                EndustriyelTransaction::create([
                    'firm' => $cari->firm->name,
                    'date' => $cari->created_at->format('Y-m-d'),
                    'amount' => $cari->tahsilat,
                    'type' => 'gelir',
                    'description' => 'Cari hesaptan tahsilat',
                    'user_id' => $cari->user_id,
                ]);
            }

            // Ödemeyi gidere ekle
            if ($cari->odeme > 0) {
                EndustriyelTransaction::create([
                    'firm' => $cari->firm->name,
                    'date' => $cari->created_at->format('Y-m-d'),
                    'amount' => $cari->odeme,
                    'type' => 'gider',
                    'description' => 'Cari hesaptan ödeme',
                    'user_id' => $cari->user_id,
                ]);
            }

            // Kayıt işlendi olarak işaretle
            $cari->update(['synced' => true]);
        }

        $this->info('Endüstriyel cari hesaplardan gelir/gider tablolarına aktarım tamamlandı.');
    }
}
