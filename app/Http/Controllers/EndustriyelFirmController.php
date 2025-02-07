<?php

namespace App\Http\Controllers;

use App\Models\EndustriyelFirm;
use App\Models\EndustriyelCariAccount; // EndustriyelCariAccount modeli eklendi
use Illuminate\Http\Request;

class EndustriyelFirmController extends Controller
{
    public function index()
    {
        // Tüm firmaları ve cari hesap özetlerini al
        $firms = EndustriyelFirm::with('cariAccounts')->get(); // İlişkili cari hesapları da al
        return view('endustriyel_firms.index', compact('firms'));
    }

    public function create()
    {
        // Yeni firma oluşturma formu görünümünü döndür
        return view('endustriyel_firms.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Yeni firma oluştur
        $firm = EndustriyelFirm::create([
            'name' => $request->name,
        ]);

        // Yeni firmaya otomatik bir cari hesap oluşturulabilir (isteğe bağlı)
        EndustriyelCariAccount::create([
            'firm_id' => $firm->id,
            'firm_name' => $firm->name,
            'tahsilat' => 0,
            'odeme' => 0,
            'alacak' => 0,
            'borc' => 0,
            'description' => 'Otomatik oluşturulan cari hesap',
            'user_id' => auth()->id(), // Mevcut kullanıcı ID'si eklenir
        ]);

        // Firma eklendikten sonra cari hesap ana sayfasına yönlendirme
        return redirect()->route('endustriyel.cari.index')->with('success', 'Firma ve cari hesap başarıyla eklendi.');
    }

    public function show($id)
    {
        // Belirtilen firmayı ve ilişkili cari hesapları bul ve "show" görünümüne gönder
        $firm = EndustriyelFirm::with('cariAccounts')->findOrFail($id);
        return view('endustriyel_firms.show', compact('firm'));
    }

    public function edit($id)
    {
        // Belirtilen firmayı düzenleme formu için bul ve "edit" görünümüne gönder
        $firm = EndustriyelFirm::findOrFail($id);
        return view('endustriyel_firms.edit', compact('firm'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255', // Güncelleme sırasında firma adı zorunlu
        ]);

        // Belirtilen firmayı bul ve güncelle
        $firm = EndustriyelFirm::findOrFail($id);
        $firm->update([
            'name' => $request->name,
        ]);

        // İlişkili cari hesaplardaki firma adını da güncelle
        EndustriyelCariAccount::where('firm_id', $firm->id)->update([
            'firm_name' => $request->name,
        ]);

        return redirect()->route('endustriyel.firms.index')->with('success', 'Firma ve ilişkili cari hesaplar başarıyla güncellendi.');
    }

    public function destroy($id)
    {
        // Belirtilen firmayı bul ve sil
        $firm = EndustriyelFirm::findOrFail($id);
        
        // İlişkili cari hesapları da sil (isteğe bağlı)
        EndustriyelCariAccount::where('firm_id', $firm->id)->delete();

        $firm->delete();

        return redirect()->route('endustriyel.firms.index')->with('success', 'Firma ve ilişkili cari hesaplar başarıyla silindi.');
    }
}
