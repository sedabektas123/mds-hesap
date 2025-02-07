@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4 text-center text-secondary">{{ request('type') == 'gelir' ? 'Yeni Gelir Ekle' : 'Yeni Gider Ekle' }}</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form method="POST" action="{{ route('endustriyel.transactions.store') }}">
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
                    <label for="date" class="form-label">Tarih</label>
                    <input type="date" class="form-control" id="date" name="date" 
                           value="{{ old('date', date('Y-m-d')) }}" required>
                </div>

                <!-- Tutar -->
                <div class="mb-3">
                    <label for="amount" class="form-label">Tutar</label>
                    <input type="number" class="form-control" id="amount" name="amount" 
                           placeholder="Tutar" step="0.01" value="{{ old('amount') }}" required>
                </div>

                <!-- Açıklama -->
                <div class="mb-3">
                    <label for="description" class="form-label">Açıklama</label>
                    <textarea class="form-control" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                </div>

                <!-- İşlem Türü (Gizli Alan) -->
                <input type="hidden" name="type" value="{{ request('type') }}">

                <!-- Gönderim Butonu -->
                <div class="text-center">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle"></i> Kaydet
                    </button>
                    <a href="{{ route('endustriyel.transactions.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Geri Dön
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
