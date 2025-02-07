<?php

namespace App\Http\Controllers;

use App\Models\EndustriyelTransaction;
use App\Models\EndustriyelFirm;
use Illuminate\Http\Request;
use Carbon\Carbon;

class EndustriyelTransactionController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->month ?? now()->month;
        $year = $request->year ?? now()->year;

        // ✅ Önceki aydan devreden bakiyeyi hesapla
        $devredenBakiye = $this->calculateDevredenBakiye($month, $year);

        // ✅ Mevcut ayın gelir ve giderlerini çek
        $gelirler = $this->getTransactionsByType('gelir', $month, $year);
        $giderler = $this->getTransactionsByType('gider', $month, $year);

        // ✅ O ayın toplam bakiyesini hesapla
        $toplamGelir = $gelirler->sum('amount');
        $toplamGider = $giderler->sum('amount');
        $toplamBakiye = $devredenBakiye + $toplamGelir - $toplamGider;

        // ✅ Sayfaya gönderilecek veriler
        $summary = [
            'tahsilat' => $toplamGelir,
            'odeme' => $toplamGider,
            'devreden_bakiye' => $devredenBakiye,
            'bakiye' => $toplamBakiye,
        ];

        return view('endustriyel_transactions.index', compact('gelirler', 'giderler', 'summary', 'month', 'year'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'firm_id' => 'nullable|exists:endustriyel_firms,id',
            'firm' => 'nullable|string|max:255',
            'amount' => 'required|numeric|min:1',
            'description' => 'nullable|string|max:255',
            'type' => 'required|in:gelir,gider',
            'date' => 'nullable|date',
        ]);

        $firmId = $request->firm_id ?? null;
        $firmName = $request->firm ?? ($firmId ? EndustriyelFirm::find($firmId)->name : null);
        $transactionDate = Carbon::parse($request->date ?? now());

        EndustriyelTransaction::create([
            'firm_id' => $firmId,
            'firm' => $firmName,
            'amount' => $request->amount,
            'description' => $request->description,
            'type' => $request->type,
            'user_id' => auth()->id(),
            'date' => $transactionDate->toDateString(),
        ]);

        return redirect()->route('endustriyel.transactions.index')->with('success', 'İşlem başarıyla kaydedildi.');
    }

    private function getTransactionsByType($type, $month, $year)
    {
        return EndustriyelTransaction::where('type', $type)
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->get();
    }

    // ✅ Geçen Aydan Devreden Bakiyeyi Hesaplayan Fonksiyon
    private function calculateDevredenBakiye($month, $year)
    {
        // ✅ Eğer Ocak ayındaysak, devreden bakiye sıfır kabul edilir.
        if ($month == 1) {
            return 0;
        }

        // ✅ Önceki ayı ve yılı belirle
        $previousMonth = $month - 1;
        $previousYear = $year;

        if ($previousMonth == 0) {
            $previousMonth = 12;
            $previousYear--;
        }

        // ✅ Önceki ayın toplam gelir ve giderini çek
        $previousMonthGelir = $this->getTransactionSum('gelir', null, $previousMonth, $previousYear);
        $previousMonthGider = $this->getTransactionSum('gider', null, $previousMonth, $previousYear);

        // ✅ Önceki aydan devreden bakiye
        $previousDevredenBakiye = $this->calculateDevredenBakiye($previousMonth, $previousYear);

        // ✅ Hesaplama: (Önceki aya devreden) + (Önceki ayın gelirleri) - (Önceki ayın giderleri)
        return $previousDevredenBakiye + $previousMonthGelir - $previousMonthGider;
    }

    private function getTransactionSum($type, $firmId = null, $month, $year)
    {
        return EndustriyelTransaction::where('type', $type)
            ->when($firmId, function ($query) use ($firmId) {
                $query->where('firm_id', $firmId);
            })
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->sum('amount');
    }

    public function destroy($id)
    {
        EndustriyelTransaction::findOrFail($id)->delete();
        return redirect()->route('endustriyel.transactions.index')->with('success', 'İşlem başarıyla silindi.');
    }

    public function create(Request $request)
    {
        $type = $request->get('type');

        if (!in_array($type, ['gelir', 'gider'])) {
            return redirect()->route('endustriyel.transactions.index')->withErrors(['message' => 'Geçersiz işlem türü.']);
        }

        return view('endustriyel_transactions.create', compact('type'));
    }
}
