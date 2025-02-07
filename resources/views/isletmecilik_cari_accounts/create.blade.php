@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4 text-center text-secondary">Yeni Cari Hesap Ekle</h2>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form action="{{ route('isletmecilik.cari.store') }}" method="POST">
                @csrf

                <!-- Firma Seçimi -->
                <div class="mb-3">
                    <label for="firm_id" class="form-label">Firma Seç</label>
                    <select name="firm_id" id="firm_id" class="form-select" required>
                        <option value="">Firma seçin</option>
                        @foreach ($firms as $firm)
                            <option value="{{ $firm->id }}">{{ $firm->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- İşlem Tarihi -->
                <div class="mb-3">
                    <label for="islem_tarihi" class="form-label">İşlem Tarihi</label>
                    <input type="date" class="form-control" id="islem_tarihi" name="islem_tarihi" 
                           value="{{ old('islem_tarihi', date('Y-m-d')) }}" required>
                </div>

                <!-- Tahsilat -->
                <div class="mb-3">
                    <label for="tahsilat" class="form-label">Tahsilat</label>
                    <input type="number" class="form-control" id="tahsilat" name="tahsilat" placeholder="0">
                </div>

                <!-- Ödeme -->
                <div class="mb-3">
                    <label for="odeme" class="form-label">Ödeme</label>
                    <input type="number" class="form-control" id="odeme" name="odeme" placeholder="0">
                </div>

                <!-- Alacak -->
                <div class="mb-3">
                    <label for="alacak" class="form-label">Alacak</label>
                    <input type="number" class="form-control" id="alacak" name="alacak" placeholder="0">
                </div>

                <!-- Borç -->
                <div class="mb-3">
                    <label for="borc" class="form-label">Borç</label>
                    <input type="number" class="form-control" id="borc" name="borc" placeholder="0">
                </div>

                <!-- Açıklama -->
                <div class="mb-3">
                    <label for="description" class="form-label">Açıklama</label>
                    <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                </div>

                <!-- Gönderim Butonu -->
                <div class="text-center">
                    <button type="submit" class="btn btn-primary">Kaydet</button>
                    <a href="{{ route('isletmecilik.cari.index') }}" class="btn btn-secondary">Geri Dön</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
