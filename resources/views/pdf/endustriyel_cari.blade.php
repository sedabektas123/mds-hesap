<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Endüstriyel Cari Hesap Listesi</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>Endüstriyel Cari Hesap Listesi</h1>
    <table>
        <thead>
            <tr>
                <th>Firma</th>
                <th>Tahsilat</th>
                <th>Ödeme</th>
                <th>Bakiye</th>
            </tr>
        </thead>
        <tbody>
            @foreach($summary as $item)
            <tr>
                <td>{{ $item['firm'] }}</td>
                <td>{{ number_format($item['tahsilat'], 2, ',', '.') }} ₺</td>
                <td>{{ number_format($item['odeme'], 2, ',', '.') }} ₺</td>
                <td>{{ number_format($item['bakiye'], 2, ',', '.') }} ₺</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
