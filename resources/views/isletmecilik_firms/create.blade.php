@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Yeni Firma Ekle</h1>
    <form action="{{ route('isletmecilik.firms.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Firma Adı</label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Kaydet</button>
    </form>
</div>
@endsection
