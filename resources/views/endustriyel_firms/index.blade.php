@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="my-4 text-primary">Endüstriyel Firmalar</h1>
    <a href="{{ route('endustriyel.firms.create') }}" class="btn btn-success mb-3">+ Yeni Firma Ekle</a>
    <table class="table table-bordered">
        <thead class="table-primary">
            <tr>
                <th>#</th>
                <th>Firma Adı</th>
                <th>İşlemler</th>
            </tr>
        </thead>
        <tbody>
            @foreach($firms as $firm)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $firm->name }}</td>
                <td>
                    <form action="{{ route('endustriyel.firms.destroy', $firm->id) }}" method="POST" onsubmit="return confirm('Bu firmayı silmek istediğinize emin misiniz?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Sil</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
