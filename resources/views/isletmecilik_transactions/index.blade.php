@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="text-center mb-4 text-secondary">İşletmecilik Gelir-Gider Yönetimi</h1>

    <!-- Filtreleme Formu -->
    <form method="GET" action="{{ route('isletmecilik.transactions.index') }}" class="mb-4">
        <div class="row g-3">
            <div class="col-md-4">
                @php
                    $turkceAylar = [
                        1 => 'Ocak', 2 => 'Şubat', 3 => 'Mart', 4 => 'Nisan',
                        5 => 'Mayıs', 6 => 'Haziran', 7 => 'Temmuz', 8 => 'Ağustos',
                        9 => 'Eylül', 10 => 'Ekim', 11 => 'Kasım', 12 => 'Aralık'
                    ];
                @endphp
                <select name="month" class="form-select">
                    <option value="">Ay Seçin</option>
                    @foreach($turkceAylar as $numara => $ay)
                        <option value="{{ $numara }}" {{ request('month') == $numara ? 'selected' : '' }}>
                            {{ $ay }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <select name="year" class="form-select">
                    <option value="">Yıl Seçin</option>
                    @foreach(range(date('Y'), 2030) as $year)
                        <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-secondary w-100">
                    <i class="bi bi-funnel"></i> Filtrele
                </button>
                <a href="{{ url('/') }}" class="btn btn-dark w-100">
                    <i class="bi bi-arrow-left"></i> Geri Dön
                </a>
            </div>
        </div>
    </form>

    <!-- Toplam Bilgiler -->
    <div class="row mb-4 g-3 justify-content-center">
        <div class="col-lg-3 col-md-6 col-12">
            <div class="card bg-light shadow-sm text-center">
                <div class="card-header text-secondary">Toplam Gelir</div>
                <div class="card-body">
                    <h3 class="text-success">{{ number_format($summary['tahsilat'] ?? 0, 2) }} ₺</h3>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-12">
            <div class="card bg-light shadow-sm text-center">
                <div class="card-header text-secondary">Toplam Gider</div>
                <div class="card-body">
                    <h3 class="text-danger">{{ number_format($summary['odeme'] ?? 0, 2) }} ₺</h3>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-12">
            <div class="card bg-light shadow-sm text-center">
                <div class="card-header text-secondary">Geçen Aydan Devreden Bakiye</div>
                <div class="card-body">
                    <h3 class="text-primary">{{ number_format($summary['devreden_bakiye'] ?? 0, 2) }} ₺</h3>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-12">
            <div class="card bg-light shadow-sm text-center">
                <div class="card-header text-secondary">Toplam Bakiye</div>
                <div class="card-body">
                    <h3 class="text-info">{{ number_format($summary['bakiye'] ?? 0, 2) }} ₺</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Gelir ve Gider Tabloları -->
    <div class="row">
        <!-- Gelir Tablosu -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-success text-white">Gelirler</div>
                <div class="card-body table-responsive">
                    <table class="table table-hover table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Firma Adı</th>
                                <th>Kullanıcı</th>
                                <th>Açıklama</th>
                                <th>Tutar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($gelirler as $index => $gelir)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $gelir->firm ?? 'Tanımsız Firma' }}</td>
                                    <td>{{ $gelir->user->name ?? 'Tanımsız Kullanıcı' }}</td>
                                    <td>{{ $gelir->description }}</td>
                                    <td class="text-success">{{ number_format($gelir->amount, 2) }} ₺</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">Gelir kaydı bulunamadı.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Gider Tablosu -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-danger text-white">Giderler</div>
                <div class="card-body table-responsive">
                    <table class="table table-hover table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Firma Adı</th>
                                <th>Kullanıcı</th>
                                <th>Açıklama</th>
                                <th>Tutar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($giderler as $index => $gider)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $gider->firm ?? 'Tanımsız Firma' }}</td>
                                    <td>{{ $gider->user->name ?? 'Tanımsız Kullanıcı' }}</td>
                                    <td>{{ $gider->description }}</td>
                                    <td class="text-danger">{{ number_format($gider->amount, 2) }} ₺</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">Gider kaydı bulunamadı.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
