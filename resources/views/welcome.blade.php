<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>MDS Hesap Yönetim Paneli</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <!-- Header -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="#">MDS Hesap Yönetimi</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    @auth
                        <li class="nav-item">
                            <a class="nav-link" href="#">{{ Auth::user()->name }}</a>
                        </li>
                        <li class="nav-item">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="btn btn-link nav-link">Çıkış Yap</button>
                            </form>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Giriş Yap</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Content -->
    <div class="container my-5">
        <h1 class="text-center mb-5">MDS Hesap Yönetim Paneli</h1>

        <div class="row g-4">
            <!-- Endüstriyel Cari Hesaplar -->
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <h5 class="card-title text-primary">Endüstriyel Cari Hesaplar</h5>
                        <p class="card-text">Endüstriyel cari hesapları görüntüle, ekle ve yönet.</p>
                        <a href="{{ route('endustriyel.cari.index') }}" class="btn btn-outline-primary w-100">Görüntüle</a>
                    </div>
                </div>
            </div>

            <!-- İşletmecilik Cari Hesaplar -->
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <h5 class="card-title text-secondary">İşletmecilik Cari Hesaplar</h5>
                        <p class="card-text">İşletmecilik cari hesaplarını görüntüle, ekle ve yönet.</p>
                        <a href="{{ route('isletmecilik.cari.index') }}" class="btn btn-outline-secondary w-100">Görüntüle</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mt-4">
            <!-- Endüstriyel Gelir Gider -->
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <h5 class="card-title text-success">Endüstriyel Gelir ve Gider</h5>
                        <p class="card-text">Endüstriyel gelir ve gider işlemlerini yönet.</p>
                        <a href="{{ route('endustriyel.transactions.index') }}" class="btn btn-outline-success w-100">Görüntüle</a>
                    </div>
                </div>
            </div>

            <!-- İşletmecilik Gelir Gider -->
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <h5 class="card-title text-warning">İşletmecilik Gelir ve Gider</h5>
                        <p class="card-text">İşletmecilik gelir ve gider işlemlerini yönet.</p>
                        <a href="{{ route('isletmecilik.transactions.index') }}" class="btn btn-outline-warning w-100">Görüntüle</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-white text-center py-3 shadow-sm">
        <div class="container">
            <p class="mb-0">© 2025 MDS Hesap Yönetim Paneli</p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
