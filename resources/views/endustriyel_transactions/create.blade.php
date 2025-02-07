@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4 text-center">{{ request('type') == 'gelir' ? 'Yeni Gelir Ekle' : 'Yeni Gider Ekle' }}</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form method="POST" action="{{ route('endustriyel.transactions.store') }}">
    @csrf
    <div class="form-group">
        <label for="firm">(Firma, Fiş, Fatura vb.)</label>
        <input type="text" class="form-control" id="firm" name="firm" placeholder="Firma veya Fiş/Fatura Bilgisi" value="{{ old('firm') }}" required>
    </div>
    <div class="form-group">
        <label for="date">Tarih</label>
        <input type="date" class="form-control" id="date" name="date" value="{{ old('date', date('Y-m-d')) }}" required>
    </div>
    <div class="form-group">
        <label for="amount">Tutar</label>
        <input type="number" class="form-control" id="amount" name="amount" placeholder="Tutar" step="0.01" value="{{ old('amount') }}" required>
    </div>
    <div class="form-group">
        <label for="description">Açıklama</label>
        <input type="text" class="form-control" id="description" name="description" placeholder="Açıklama" value="{{ old('description') }}">
    </div>
    <input type="hidden" name="type" value="{{ request('type') }}">
    <button type="submit" class="btn btn-primary">Kaydet</button>
</form>

</div>
@endsection
