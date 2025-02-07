@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="text-center mb-4 text-secondary">İşletmecilik Cari Hesap Listesi</h1>

    <!-- Toplam Bilgiler -->
    <div class="row mb-4 g-3 justify-content-center">
        <div class="col-lg-2 col-md-4 col-6">
            <div class="card shadow-sm border border-secondary">
                <div class="card-header text-secondary bg-light">Toplam Tahsilat</div>
                <div class="card-body bg-white text-center">
                    {{ number_format(isset($totalTahsilat) ? $totalTahsilat : 0, 2) }} ₺
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-6">
            <div class="card shadow-sm border border-secondary">
                <div class="card-header text-secondary bg-light">Toplam Ödeme</div>
                <div class="card-body bg-white text-center">
                    <h4 class="text-secondary">{{ number_format($totalOdeme, 2) }} ₺</h4>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-6">
            <div class="card shadow-sm border border-secondary">
                <div class="card-header text-secondary bg-light">Toplam Alacak</div>
                <div class="card-body bg-white text-center">
                    <h4 class="text-success">{{ number_format($totalAlacak, 2) }} ₺</h4>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-6">
            <div class="card shadow-sm border border-secondary">
                <div class="card-header text-secondary bg-light">Toplam Borç</div>
                <div class="card-body bg-white text-center">
                    <h4 class="text-danger">{{ number_format($totalBorc, 2) }} ₺</h4>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-6">
            <div class="card shadow-sm border border-secondary">
                <div class="card-header text-secondary bg-light">Toplam Bakiye</div>
                <div class="card-body bg-white text-center">
                    <h4 class="text-primary">{{ number_format($totalBakiye, 2) }} ₺</h4>
                </div>
            </div>
        </div>
    </div>

    <!-- İşlem Butonları -->
    <div class="row mb-4 text-center g-2">
        <div class="col-md-4 col-12">
            <a href="{{ route('isletmecilik.cari.create') }}" class="btn btn-dark w-100">
                <i class="bi bi-plus-circle"></i> Yeni Cari Hesap Ekle
            </a>
        </div>
        <div class="col-md-4 col-12">
            <a href="{{ route('isletmecilik.firms.create') }}" class="btn btn-dark w-100">
                <i class="bi bi-building"></i> Yeni Firma Ekle
            </a>
        </div>
        <div class="col-md-4 col-12">
            <a href="{{ url('/') }}" class="btn btn-dark w-100">
                <i class="bi bi-arrow-left"></i> Geri Dön
            </a>
        </div>
    </div>

    <!-- Tablo -->
    <div class="card shadow-sm border border-secondary">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered text-center align-middle bg-white">
                    <thead class="table-secondary">
                        <tr>
                            <th>Firma</th>
                            <th>Tahsilat</th>
                            <th>Ödeme</th>
                            <th>Alacak</th>
                            <th>Borç</th>
                            <th>Bakiye</th>
                            <th>İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($summary as $row)
                        <tr>
                            <td class="text-secondary">{{ $row['firm'] }}</td>
                            <td class="text-success">{{ number_format($row['tahsilat'], 2) }} ₺</td>
                            <td class="text-danger">{{ number_format($row['odeme'], 2) }} ₺</td>
                            <td class="text-primary">{{ number_format($row['alacak'], 2) }} ₺</td>
                            <td class="text-warning">{{ number_format($row['borc'], 2) }} ₺</td>
                            <td class="text-info">{{ number_format($row['bakiye'], 2) }} ₺</td>
                            <td>
                                <a href="{{ route('isletmecilik.cari.show', $row['id']) }}" class="btn btn-dark btn-sm">
                                    <i class="bi bi-eye"></i> Detay
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div> <!-- .table-responsive Bitiş -->
        </div>
    </div>
</div>
@endsection
