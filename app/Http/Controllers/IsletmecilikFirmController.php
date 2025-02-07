<?php

namespace App\Http\Controllers;

use App\Models\IsletmecilikFirm;
use Illuminate\Http\Request;

class IsletmecilikFirmController extends Controller
{
    public function store(Request $request)
    {
        // Firma adı doğrulama
        $request->validate([
            'name' => 'required|string|max:255|unique:isletmecilik_firms,name', // isletmecilik_firms tablosuna göre unique kontrol
        ]);

        // Yeni firma oluştur
        IsletmecilikFirm::create([
            'name' => $request->name,
        ]);

        // Kullanıcıyı işletmecilik cari sayfasına yönlendir
        return redirect()->route('isletmecilik.cari.index')->with('success', 'Firma başarıyla eklendi.');
    }

    public function index()
    {
        // Tüm firmaları listele
        $firms = IsletmecilikFirm::all();

        return view('isletmecilik_firms.index', compact('firms'));
    }

    public function create()
    {
        // Yeni firma ekleme formu
        return view('isletmecilik_firms.create');
    }

    public function destroy($id)
    {
        // Firma silme işlemi
        $firm = IsletmecilikFirm::findOrFail($id);
        $firm->delete();

        return redirect()->route('isletmecilik.firms.index')->with('success', 'Firma başarıyla silindi.');
    }
}
