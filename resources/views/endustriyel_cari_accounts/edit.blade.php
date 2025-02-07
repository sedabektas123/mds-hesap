@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4 text-center text-secondary">Cari Hesap Güncelle</h2>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            @if(isset($cariAccount))
            <form action="{{ route('endustriyel.cari.update', $cariAccount->id) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Tarih -->
                <div class="mb-3">
                    <label for="islem_tarihi" class="form-label">Tarih</label>
                    <input type="date" class="form-control" id="islem_tarihi" name="islem_tarihi" 
                           value="{{ old('islem_tarihi', isset($cariAccount->date) ? \Carbon\Carbon::parse($cariAccount->date)->format('Y-m-d') : now()->format('Y-m-d')) }}">
                </div>

                <!-- Tahsilat -->
                <div class="mb-3">
                    <label for="tahsilat" class="form-label">Tahsilat</label>
                    <input type="number" step="0.01" class="form-control" id="tahsilat" name="tahsilat" 
                           value="{{ old('tahsilat', $cariAccount->tahsilat ?? 0) }}">
                </div>

                <!-- Ödeme -->
                <div class="mb-3">
                    <label for="odeme" class="form-label">Ödeme</label>
                    <input type="number" step="0.01" class="form-control" id="odeme" name="odeme" 
                           value="{{ old('odeme', $cariAccount->odeme ?? 0) }}">
                </div>

                <!-- Alacak -->
                <div class="mb-3">
                    <label for="alacak" class="form-label">Alacak</label>
                    <input type="number" step="0.01" class="form-control" id="alacak" name="alacak" 
                           value="{{ old('alacak', $cariAccount->alacak ?? 0) }}">
                </div>

                <!-- Borç -->
                <div class="mb-3">
                    <label for="borc" class="form-label">Borç</label>
                    <input type="number" step="0.01" class="form-control" id="borc" name="borc" 
                           value="{{ old('borc', $cariAccount->borc ?? 0) }}">
                </div>

                <!-- Açıklama -->
                <div class="mb-3">
                    <label for="description" class="form-label">Açıklama</label>
                    <textarea class="form-control" id="description" name="description" rows="3">{{ old('description', $cariAccount->description ?? '') }}</textarea>
                </div>

                <!-- Butonlar -->
                <div class="text-center">
                    <button type="submit" class="btn btn-primary">Güncelle</button>
                    <a href="{{ route('endustriyel.cari.index') }}" class="btn btn-secondary">Geri Dön</a>
                </div>
            </form>
            @else
                <div class="alert alert-danger text-center">
                    Cari hesap bulunamadı.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
