<?php

namespace App\Http\Controllers;

use App\Models\EndustriyelCariAccount;
use App\Models\EndustriyelFirm;
use App\Models\EndustriyelTransaction;
use Illuminate\Http\Request;
use Carbon\Carbon;

class EndustriyelCariAccountController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->month ?? now()->month;
        $year = $request->year ?? now()->year;

        $firms = EndustriyelFirm::with(['cariAccounts' => function ($query) {
            $query->where('date', '<=', Carbon::now()->endOfYear()); // 🔥 Geçmiş ve gelecekteki işlemleri al
        }])->get();

        $summary = $firms->map(function ($firm) {
            $tahsilat = $firm->cariAccounts->sum('tahsilat');
            $odeme = $firm->cariAccounts->sum('odeme');
            $alacak = $firm->cariAccounts->sum('alacak');
            $borc = $firm->cariAccounts->sum('borc');

            return [
                'id' => $firm->id,
                'firm' => $firm->name,
                'tahsilat' => $tahsilat,
                'odeme' => $odeme,
                'alacak' => $alacak,
                'borc' => $borc,
                'bakiye' => $tahsilat - $odeme,
            ];
        });

        $totalTahsilat = $summary->sum('tahsilat');
        $totalOdeme = $summary->sum('odeme');
        $totalAlacak = $summary->sum('alacak');
        $totalBorc = $summary->sum('borc');
        $totalBakiye = $totalTahsilat - $totalOdeme;

        return view('endustriyel_cari_accounts.index', compact(
            'summary', 'totalTahsilat', 'totalOdeme', 'totalAlacak', 'totalBorc', 'totalBakiye', 'month', 'year'
        ));
    }

    public function create()
    {
        $firms = EndustriyelFirm::all();
        return view('endustriyel_cari_accounts.create', compact('firms'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'firm_id' => 'required|exists:endustriyel_firms,id',
            'islem_tarihi' => 'required|date',  // 🔥 Eğer formda "islem_tarihi" kullanılıyorsa
            'tahsilat' => 'nullable|numeric|min:0',
            'odeme' => 'nullable|numeric|min:0',
            'alacak' => 'nullable|numeric|min:0',
            'borc' => 'nullable|numeric|min:0',
            'description' => 'nullable|string|max:255',
        ]);
    
        $firm = EndustriyelFirm::findOrFail($request->firm_id);
    
        $cariAccount = EndustriyelCariAccount::create([
            'firm_id' => $firm->id,
            'firm_name' => $firm->name,
            'date' => Carbon::parse($request->islem_tarihi)->format('Y-m-d'),  // 🔥 Tarih hatasını düzelttik
            'tahsilat' => $request->tahsilat ?? 0,
            'odeme' => $request->odeme ?? 0,
            'alacak' => $request->alacak ?? 0,
            'borc' => $request->borc ?? 0,
            'description' => $request->description,
            'user_id' => auth()->id(),
        ]);
    
        $this->syncToTransactions($cariAccount);
    
        return redirect()->route('endustriyel.cari.index')->with('success', 'Cari hesap başarıyla eklendi.');
    }
    

    public function edit($id)
    {
        $cariAccount = EndustriyelCariAccount::findOrFail($id);
        return view('endustriyel_cari_accounts.edit', compact('cariAccount'));
    }

    public function update(Request $request, $id)
    {
        $cariAccount = EndustriyelCariAccount::find($id);
    
        if (!$cariAccount) {
            return redirect()->route('endustriyel.cari.index')->withErrors(['message' => 'Cari hesap bulunamadı.']);
        }
    
        $request->validate([
            'islem_tarihi' => 'required|date',
            'tahsilat' => 'nullable|numeric|min:0',
            'odeme' => 'nullable|numeric|min:0',
            'alacak' => 'nullable|numeric|min:0',
            'borc' => 'nullable|numeric|min:0',
            'description' => 'nullable|string|max:255',
        ]);
    
        // ✅ Güncelleme işlemi
        $cariAccount->update([
            'date' => Carbon::parse($request->islem_tarihi)->format('Y-m-d'),
            'tahsilat' => $request->tahsilat ?? 0,
            'odeme' => $request->odeme ?? 0,
            'alacak' => $request->alacak ?? 0,
            'borc' => $request->borc ?? 0,
            'description' => $request->description,
        ]);
    
        // ✅ Eski Gelir-Gider Kayıtlarını Sil ve Yeni Veriyi Ekle
        $this->syncToTransactions($cariAccount, true);
    
        return redirect()->route('endustriyel.cari.index')->with('success', 'Cari hesap başarıyla güncellendi.');
    }
    

    private function syncToTransactions($cariAccount, $update = false)
    {
        $transactionDate = Carbon::parse($cariAccount->date)->format('Y-m-d');
    
        // ✅ Güncelleme modundaysak, önce eski kayıtları siliyoruz
        if ($update) {
            EndustriyelTransaction::where('firm_id', $cariAccount->firm_id)
                ->where('date', $transactionDate)
                ->delete();
        }
    
        // ✅ Yeni Tahsilat Kaydı
        if ($cariAccount->tahsilat > 0) {
            EndustriyelTransaction::create([
                'firm_id' => $cariAccount->firm_id,
                'firm' => $cariAccount->firm_name,
                'amount' => $cariAccount->tahsilat,
                'type' => 'gelir',
                'description' => $cariAccount->description ?? 'Tahsilat - Açıklama Yok',
                'date' => $transactionDate,
                'user_id' => $cariAccount->user_id,
            ]);
        }
    
        // ✅ Yeni Ödeme Kaydı
        if ($cariAccount->odeme > 0) {
            EndustriyelTransaction::create([
                'firm_id' => $cariAccount->firm_id,
                'firm' => $cariAccount->firm_name,
                'amount' => $cariAccount->odeme,
                'type' => 'gider',
                'description' => $cariAccount->description ?? 'Ödeme - Açıklama Yok',
                'date' => $transactionDate,
                'user_id' => $cariAccount->user_id,
            ]);
        }
    }
    
    public function destroy($id)
    {
        $cariHesap = EndustriyelCariAccount::find($id);
    
        if (!$cariHesap) {
            return redirect()->route('endustriyel.cari.index')->withErrors(['message' => 'Cari hesap bulunamadı.']);
        }
    
        // ✅ İlgili Gelir-Gider Kayıtlarını Sil
        EndustriyelTransaction::where('firm_id', $cariHesap->firm_id)
            ->where('date', $cariHesap->date)
            ->delete();
    
        // ✅ Cari Hesabı Sil
        $cariHesap->delete();
    
        return redirect()->route('endustriyel.cari.index')->with('success', 'Cari hesap ve ilgili gelir-gider işlemleri başarıyla silindi.');
    }
    
    public function show($firm_id)
    {
        $firmAccounts = EndustriyelCariAccount::where('firm_id', $firm_id)
            ->with('user')
            ->get();

        $totalTahsilat = $firmAccounts->sum('tahsilat');
        $totalOdeme = $firmAccounts->sum('odeme');
        $totalAlacak = $firmAccounts->sum('alacak');
        $totalBorc = $firmAccounts->sum('borc');
        $totalBakiye = ($totalTahsilat ?? 0) - ($totalOdeme ?? 0);

        $firm = EndustriyelFirm::findOrFail($firm_id);

        return view('endustriyel_cari_accounts.show', compact(
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
