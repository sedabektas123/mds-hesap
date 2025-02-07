@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4 text-center text-secondary">Endüstriyel Cari Hesap Detayları</h2>

    <!-- Firma Bilgisi -->
    <div class="card mb-4 shadow-sm border-0">
        <div class="card-body bg-dark text-white">
            <h4>Firma: <strong>{{ $firm->name }}</strong></h4>
        </div>
    </div>

    <!-- İşlemler Tablosu -->
    <div class="card mb-4 shadow-sm border-0">
        <div class="card-body">
            <h5 class="mb-3 text-secondary">İşlemler</h5>
            <div class="table-responsive-sm"> <!-- Mobil uyum için div ekleniyor -->
                <table class="table table-hover table-striped bg-light">
                    <thead class="bg-secondary text-white">
                        <tr>
                            <th>#</th>
                            <th>Tarih</th>
                            <th>Kullanıcı</th>
                            <th>Tahsilat</th>
                            <th>Ödeme</th>
                            <th>Alacak</th>
                            <th>Borç</th>
                            <th>Açıklama</th>
                            <th>İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($firmAccounts as $index => $account)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $account->date ? \Carbon\Carbon::parse($account->date)->format('d/m/Y') : '-' }}</td>
                            <td>{{ $account->user ? $account->user->name : 'Tanımsız Kullanıcı' }}</td>
                            <td>{{ number_format($account->tahsilat, 2) }} ₺</td>
                            <td>{{ number_format($account->odeme, 2) }} ₺</td>
                            <td>{{ number_format($account->alacak, 2) }} ₺</td>
                            <td>{{ number_format($account->borc, 2) }} ₺</td>
                            <td>{{ $account->description ?? '-' }}</td> <!-- Açıklama alanı düzeltildi -->
                            <td class="d-flex gap-2">
                                <a href="{{ route('endustriyel.cari.edit', $account->id) }}" class="btn btn-dark btn-sm">Güncelle</a>
                                <form action="{{ route('endustriyel.cari.destroy', $account->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bu işlemi silmek istediğinizden emin misiniz?')">Sil</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center text-secondary">Henüz işlem bulunmamaktadır.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Özet Bilgiler -->
    <div class="row g-3">
        @php
            $summary = [
                ['title' => 'Toplam Tahsilat', 'value' => $totalTahsilat, 'class' => 'bg-dark text-white'],
                ['title' => 'Toplam Ödeme', 'value' => $totalOdeme, 'class' => 'bg-dark text-white'],
                ['title' => 'Toplam Bakiye', 'value' => $totalBakiye, 'class' => 'bg-dark text-white'],
                ['title' => 'Toplam Alacak', 'value' => $totalAlacak, 'class' => 'bg-dark text-white'],
                ['title' => 'Toplam Borç', 'value' => $totalBorc, 'class' => 'bg-dark text-white']
            ];
        @endphp

        @foreach($summary as $item)
        <div class="col-md-2 col-sm-6">
            <div class="card text-center {{ $item['class'] }} shadow-sm border-0">
                <div class="card-body">
                    <h6 class="card-title text-secondary">{{ $item['title'] }}</h6>
                    <p class="h5">{{ number_format($item['value'], 2) }} ₺</p>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Geri Dön Butonu -->
    <div class="text-center mt-4">
        <a href="{{ route('endustriyel.cari.index') }}" class="btn btn-secondary">Geri Dön</a>
    </div>
</div>
@endsection
