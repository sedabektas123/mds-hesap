@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Firma Düzenle</h1>
    <form action="{{ route('isletmecilik.firms.update', $firm->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="name" class="form-label">Firma Adı</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ $firm->name }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Güncelle</button>
    </form>
</div>
@endsection
