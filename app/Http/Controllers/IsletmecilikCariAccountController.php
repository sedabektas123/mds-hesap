<?php

namespace App\Http\Controllers;

use App\Models\IsletmecilikCariAccount;
use App\Models\IsletmecilikFirm;
use App\Models\IsletmecilikTransaction;
use Illuminate\Http\Request;
use Carbon\Carbon;

class IsletmecilikCariAccountController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->month ?? now()->month;
        $year  = $request->year ?? now()->year;

        // Firmaların cari hesaplarını alıyoruz
        $firms = IsletmecilikFirm::with(['cariAccounts' => function ($query) {
            // İsteğe bağlı, hangi tarih aralığını göstereceğinizi burada belirleyebilirsiniz
            $query->where('date', '<=', Carbon::now()->endOfYear());
        }])->get();

        // Her firma için tahsilat, ödeme vb. bilgilerini topluyoruz
        $summary = $firms->map(function ($firm) {
            $tahsilat = $firm->cariAccounts->sum('tahsilat');
            $odeme    = $firm->cariAccounts->sum('odeme');
            $alacak   = $firm->cariAccounts->sum('alacak');
            $borc     = $firm->cariAccounts->sum('borc');

            return [
                'id'       => $firm->id,
                'firm'     => $firm->name,
                'tahsilat' => $tahsilat,
                'odeme'    => $odeme,
                'alacak'   => $alacak,
                'borc'     => $borc,
                'bakiye'   => $tahsilat - $odeme,
            ];
        });

        // Toplam değerler
        $totalTahsilat = $summary->sum('tahsilat');
        $totalOdeme    = $summary->sum('odeme');
        $totalAlacak   = $summary->sum('alacak');
        $totalBorc     = $summary->sum('borc');
        $totalBakiye   = $totalTahsilat - $totalOdeme;

        return view('isletmecilik_cari_accounts.index', compact(
            'summary',
            'totalTahsilat',
            'totalOdeme',
            'totalAlacak',
            'totalBorc',
            'totalBakiye',
            'month',
            'year'
        ));
    }

    public function create()
    {
        $firms = IsletmecilikFirm::all();
        return view('isletmecilik_cari_accounts.create', compact('firms'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'firm_id'      => 'required|exists:isletmecilik_firms,id',
            'islem_tarihi' => 'required|date',
            'tahsilat'     => 'nullable|numeric|min:0',
            'odeme'        => 'nullable|numeric|min:0',
            'alacak'       => 'nullable|numeric|min:0',
            'borc'         => 'nullable|numeric|min:0',
            'description'  => 'nullable|string|max:255',
        ]);

        $firm = IsletmecilikFirm::findOrFail($request->firm_id);

        $cariAccount = IsletmecilikCariAccount::create([
            'firm_id'   => $firm->id,
            'firm_name' => $firm->name,
            'date'      => Carbon::parse($request->islem_tarihi)->format('Y-m-d'),
            'tahsilat'  => $request->tahsilat ?? 0,
            'odeme'     => $request->odeme ?? 0,
            'alacak'    => $request->alacak ?? 0,
            'borc'      => $request->borc ?? 0,
            'description' => $request->description,
            'user_id'   => auth()->id(),
        ]);

        // Cari hesabı transaction tablosuna senkronluyoruz
        $this->syncToTransactions($cariAccount);

        return redirect()->route('isletmecilik.cari.index')->with('success', 'Cari hesap başarıyla eklendi.');
    }

    public function edit($id)
    {
        $cariAccount = IsletmecilikCariAccount::findOrFail($id);
        return view('isletmecilik_cari_accounts.edit', compact('cariAccount'));
    }

    public function update(Request $request, $id)
    {
        $cariAccount = IsletmecilikCariAccount::find($id);

        if (!$cariAccount) {
            return redirect()->route('isletmecilik.cari.index')
                ->withErrors(['message' => 'Cari hesap bulunamadı.']);
        }

        $request->validate([
            'islem_tarihi' => 'required|date',
            'tahsilat'     => 'nullable|numeric|min:0',
            'odeme'        => 'nullable|numeric|min:0',
            'alacak'       => 'nullable|numeric|min:0',
            'borc'         => 'nullable|numeric|min:0',
            'description'  => 'nullable|string|max:255',
        ]);

        // Cari hesap güncellemesi
        $cariAccount->update([
            'date'      => Carbon::parse($request->islem_tarihi)->format('Y-m-d'),
            'tahsilat'  => $request->tahsilat ?? 0,
            'odeme'     => $request->odeme ?? 0,
            'alacak'    => $request->alacak ?? 0,
            'borc'      => $request->borc ?? 0,
            'description' => $request->description,
        ]);

        // Eski Transaction kayıtlarını silip yeni veriyi ekliyoruz
        $this->syncToTransactions($cariAccount, true);

        return redirect()->route('isletmecilik.cari.index')->with('success', 'Cari hesap başarıyla güncellendi.');
    }

    private function syncToTransactions($cariAccount, $update = false)
    {
        $transactionDate = Carbon::parse($cariAccount->date)->format('Y-m-d');

        // Güncelleme modundaysak önce eski kayıtları siliyoruz
        if ($update) {
            IsletmecilikTransaction::where('firm_id', $cariAccount->firm_id)
                ->where('date', $transactionDate)
                ->delete();
        }

        // Tahsilat => Gelir
        if ($cariAccount->tahsilat > 0) {
            IsletmecilikTransaction::create([
                'firm_id'    => $cariAccount->firm_id,
                'firm'       => $cariAccount->firm_name,
                'amount'     => $cariAccount->tahsilat,
                'type'       => 'gelir',
                'description'=> $cariAccount->description ?? 'Tahsilat - Açıklama Yok',
                'date'       => $transactionDate,
                'user_id'    => $cariAccount->user_id,
            ]);
        }

        // Ödeme => Gider
        if ($cariAccount->odeme > 0) {
            IsletmecilikTransaction::create([
                'firm_id'    => $cariAccount->firm_id,
                'firm'       => $cariAccount->firm_name,
                'amount'     => $cariAccount->odeme,
                'type'       => 'gider',
                'description'=> $cariAccount->description ?? 'Ödeme - Açıklama Yok',
                'date'       => $transactionDate,
                'user_id'    => $cariAccount->user_id,
            ]);
        }
    }

    public function destroy($id)
    {
        $cariHesap = IsletmecilikCariAccount::find($id);
    
        if (!$cariHesap) {
            return redirect()->route('isletmecilik.cari.index')
                ->withErrors(['message' => 'Cari hesap bulunamadı.']);
        }
    
        // 1) İlgili Transaction kayıtlarını bul ve sil
        //    Eğer firm_id + date gibi bir eşleştirme yapıyorsanız:
        IsletmecilikTransaction::where('firm_id', $cariHesap->firm_id)
            ->where('date', $cariHesap->date) 
            ->delete();
    
        // 2) Ardından Cari Hesabını sil
        $cariHesap->delete();
    
        return redirect()->route('isletmecilik.cari.index')
            ->with('success', 'Cari hesap ve ilgili gelir-gider işlemleri başarıyla silindi.');
    }
    
    public function show($firm_id)
    {
        $firmAccounts = IsletmecilikCariAccount::where('firm_id', $firm_id)
            ->with('user')
            ->get();

        $totalTahsilat = $firmAccounts->sum('tahsilat');
        $totalOdeme    = $firmAccounts->sum('odeme');
        $totalAlacak   = $firmAccounts->sum('alacak');
        $totalBorc     = $firmAccounts->sum('borc');
        $totalBakiye   = ($totalTahsilat ?? 0) - ($totalOdeme ?? 0);

        $firm = IsletmecilikFirm::findOrFail($firm_id);

        return view('isletmecilik_cari_accounts.show', compact(
            'firm',
            'firmAccounts',
            'totalTahsilat',
            'totalOdeme',
            'totalAlacak',
            'totalBorc',
            'totalBakiye'
        ));
    }
}
