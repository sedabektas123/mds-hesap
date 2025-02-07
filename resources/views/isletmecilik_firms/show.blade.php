@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Firma Detayları</h1>
    <p><strong>Firma Adı:</strong> {{ $firm->name }}</p>
    <a href="{{ route('isletmecilik.firms.index') }}" class="btn btn-secondary">Geri Dön</a>
</div>
@endsection
