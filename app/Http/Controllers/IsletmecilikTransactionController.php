<?php

namespace App\Http\Controllers;

use App\Models\IsletmecilikTransaction;
use App\Models\IsletmecilikCariAccount;
use App\Models\IsletmecilikFirm;
use Illuminate\Http\Request;
use Carbon\Carbon; // Carbon kullanacaksak import etmeliyiz

class IsletmecilikTransactionController extends Controller
{
    /**
     * GELİR – GİDER LİSTESİ (Devreden Bakiye Hesaplamalı)
     */
    public function index(Request $request)
    {
        // Varsayılan olarak mevcut ay ve yıl
        $month = $request->month ?? now()->month;
        $year = $request->year ?? now()->year;

        // Önceki aydan devreden bakiyeyi hesapla
        $devredenBakiye = $this->calculateDevredenBakiye($month, $year);

        // Mevcut ayın gelir ve giderlerini al
        $gelirler = $this->getTransactionsByType('gelir', $month, $year);
        $giderler = $this->getTransactionsByType('gider', $month, $year);

        // Toplam gelir/gider
        $toplamGelir = $gelirler->sum('amount');
        $toplamGider = $giderler->sum('amount');

        // Bu ayın sonunda elde edilecek bakiye
        $toplamBakiye = $devredenBakiye + $toplamGelir - $toplamGider;

        // Özet verileri view’e gönderelim
        $summary = [
            'tahsilat' => $toplamGelir,
            'odeme' => $toplamGider,
            'devreden_bakiye' => $devredenBakiye,
            'bakiye' => $toplamBakiye,
        ];

        return view('isletmecilik_transactions.index', compact('gelirler', 'giderler', 'summary', 'month', 'year'));
    }

    /**
     * YENİ İŞLEM KAYDI (Store)
     */
    public function store(Request $request)
    {
        $request->validate([
            'firm_id'     => 'nullable|exists:isletmecilik_firms,id',
            'firm'        => 'nullable|string|max:255',
            'amount'      => 'required|numeric|min:1',
            'description' => 'nullable|string|max:255',
            'type'        => 'required|in:gelir,gider',
            'date'        => 'nullable|date',
        ]);

        // Firma bilgisi
        $firmId   = $request->firm_id ?? null;
        $firmName = $request->firm ?? ($firmId ? IsletmecilikFirm::find($firmId)->name : null);

        // Tarih boş gelirse bugünün tarihi kullanılsın
        $transactionDate = $request->date ? Carbon::parse($request->date) : now();

        // Cari hesap tablosuna kayıt (opsiyonel, sizde varsa)
        IsletmecilikCariAccount::create([
            'firm_id'   => $firmId, 
            'firm_name' => $firmName,
            'tahsilat'  => $request->type === 'gelir' ? $request->amount : 0,
            'odeme'     => $request->type === 'gider' ? $request->amount : 0,
            'description' => $request->description,
            'user_id'   => auth()->id(),
        ]);

        // Transaction tablosuna kayıt
        IsletmecilikTransaction::create([
            'firm_id'    => $firmId,
            'firm'       => $firmName,
            'amount'     => $request->amount,
            'description'=> $request->description,
            'type'       => $request->type,
            'user_id'    => auth()->id(),
            'date'       => $transactionDate->toDateString(),
        ]);

        return redirect()
            ->route('isletmecilik.transactions.index')
            ->with('success', 'İşlem başarıyla kaydedildi.');
    }

    /**
     * İŞLEM GÜNCELLEME (Update)
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'amount'      => 'required|numeric|min:0',
            'type'        => 'required|in:gelir,gider',
            'description' => 'nullable|string|max:255',
            'date'        => 'nullable|date',
        ]);

        $transaction = IsletmecilikTransaction::findOrFail($id);

        $transactionDate = $request->date ? Carbon::parse($request->date) : now();

        $transaction->update([
            'amount'      => $request->amount,
            'type'        => $request->type,
            'description' => $request->description,
            'date'        => $transactionDate->toDateString(),
        ]);

        return redirect()
            ->route('isletmecilik.transactions.index')
            ->with('success', 'İşlem başarıyla güncellendi.');
    }

    /**
     * YENİ KAYIT EKRANI (Create)
     */
    public function create(Request $request)
    {
        $type = $request->get('type'); // 'gelir' veya 'gider'

        if (!in_array($type, ['gelir', 'gider'])) {
            return redirect()
                ->route('isletmecilik.transactions.index')
                ->withErrors(['message' => 'Geçersiz işlem türü.']);
        }

        return view('isletmecilik_transactions.create', compact('type'));
    }

    // ---------------------------------------------------------
    // AŞAĞIDA ENDÜSTRİYELDEN ESİNLENDİĞİMİZ ÖZEL METODLAR
    // ---------------------------------------------------------

    /**
     * Belirli ay-yıl ve türdeki (gelir/gider) işlemleri çekmek
     */
    private function getTransactionsByType($type, $month, $year)
    {
        return IsletmecilikTransaction::where('type', $type)
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->with(['firm', 'user']) // İlişkileriniz varsa
            ->get();
    }

    /**
     * Belirli ay-yıl ve türdeki (gelir/gider) toplam tutar
     */
    private function getTransactionSum($type, $firmId, $month, $year)
    {
        return IsletmecilikTransaction::where('type', $type)
            ->when($firmId, function ($query) use ($firmId) {
                $query->where('firm_id', $firmId);
            })
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->sum('amount');
    }

    /**
     * Bir önceki aydan devreden bakiye hesaplaması
     * 
     *  - Eğer Ocak ayı ise devreden bakiye = 0 (varsayım).
     *  - Değilse bir önceki ayın bakiyesini hesaplayıp üzerine ekliyoruz.
     */
    private function calculateDevredenBakiye($month, $year)
    {
        // Eğer Ocak ayındaysak, devreden bakiye 0 kabul edilir
        if ($month == 1) {
            return 0;
        }

        // Bir önceki ay & yıl
        $previousMonth = $month - 1;
        $previousYear  = $year;

        if ($previousMonth == 0) {
            $previousMonth = 12;
            $previousYear --;
        }

        // Bir önceki ayın toplam gelir ve gideri
        $previousMonthGelir = $this->getTransactionSum('gelir', null, $previousMonth, $previousYear);
        $previousMonthGider = $this->getTransactionSum('gider', null, $previousMonth, $previousYear);

        // Daha da önceki aydan devreden bakiye (recursive)
        $previousDevredenBakiye = $this->calculateDevredenBakiye($previousMonth, $previousYear);

        // Devreden Bakiye = (Önceki devreden) + (gelir) - (gider)
        return $previousDevredenBakiye + $previousMonthGelir - $previousMonthGider;
    }
}
