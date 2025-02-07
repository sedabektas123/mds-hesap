@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2 class="text-center mb-4">Gelir-Gider Güncelle</h2>
    <form action="{{ route('endustriyel.transactions.update', $transaction->id) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Firma Adı -->
        <div class="form-group mb-3">
            <label for="firm_id">Firma Adı</label>
            <select name="firm_id" id="firm_id" class="form-control">
                @foreach($firms as $firm)
                    <option value="{{ $firm->id }}" 
                        {{ $transaction->firm_id == $firm->id ? 'selected' : '' }}>
                        {{ $firm->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Tutar -->
        <div class="form-group mb-3">
            <label for="amount">Tutar</label>
            <input type="number" name="amount" id="amount" class="form-control" value="{{ $transaction->amount }}" required>
        </div>

        <!-- Açıklama -->
        <div class="form-group mb-3">
            <label for="description">Açıklama</label>
            <textarea name="description" id="description" class="form-control" rows="3">{{ $transaction->description }}</textarea>
        </div>

        <!-- Gönder Butonu -->
        <button type="submit" class="btn btn-primary">Güncelle</button>
    </form>
</div>
@endsection
