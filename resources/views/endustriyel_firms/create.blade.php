@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="text-center mb-4">Yeni Firma Ekle</h1>
    <form action="{{ route('endustriyel.firms.store') }}" method="POST">
        @csrf
        <div class="form-group mb-3">
            <label for="name">Firma Adı</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Kaydet</button>
    </form>
</div>
@endsection
