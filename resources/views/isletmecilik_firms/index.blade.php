@extends('layouts.app')

@section('content')
<div class="container">
    <h1>İşletmecilik Firmaları</h1>
    <a href="{{ route('isletmecilik.firms.create') }}" class="btn btn-primary mb-3">Yeni Firma Ekle</a>
    <table class="table table-bordered">
    <thead>
        <tr>
            <th>Firma</th>
            <th>Tahsilat</th>
            <th>Ödeme</th>
            <th>Bakiye</th>
            <th>İşlemler</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($summary as $row)
        <tr>
            <td>{{ $row['firm'] }}</td>
            <td>{{ number_format($row['tahsilat'], 2) }} ₺</td>
            <td>{{ number_format($row['odeme'], 2) }} ₺</td>
            <td>{{ number_format($row['bakiye'], 2) }} ₺</td>
            <td>
                <a href="{{ route('isletmecilik.cari.show', $row['id']) }}" class="btn btn-info btn-sm">Detay</a>
                <form action="{{ route('isletmecilik.cari.destroy', $row['id']) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bu cari hesabı silmek istediğinizden emin misiniz?')">Sil</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

</div>
@endsection
