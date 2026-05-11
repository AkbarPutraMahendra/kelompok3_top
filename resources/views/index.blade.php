<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TopUp All Game</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background-color: #f8f9fa; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .navbar { background-color: #1a1a2e; }
        .hero { background: linear-gradient(135deg, #16213e 0%, #0f3460 100%); color: white; padding: 80px 0; border-bottom-left-radius: 50px; border-bottom-right-radius: 50px; }
        .game-card { border: none; border-radius: 15px; transition: transform 0.3s; overflow: hidden; }
        .game-card:hover { transform: translateY(-10px); box-shadow: 0 10px 20px rgba(0,0,0,0.1); }
        .game-card img { height: 180px; object-fit: cover; }
        .category-title { border-left: 5px solid #e94560; padding-left: 15px; margin-bottom: 30px; }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="/"><i class="fas fa-bolt text-warning me-2"></i>TOPUP-TOP</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link active" href="/">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="/cek-transaksi">Cek Pesanan</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <header class="hero text-center">
        <div class="container">
            <h1 class="display-4 fw-bold mb-3">Top Up Game Tercepat</h1>
            <p class="lead">Proses otomatis 24 jam dengan berbagai metode pembayaran aman.</p>
        </div>
    </header>

    <main class="container my-5">
        <h3 class="category-title fw-bold">Pilih Game Populer</h3>
        
        <div class="row g-4">
            <div class="col-6 col-md-4 col-lg-3">
                <a href="/topup/mobile-legends" class="text-decoration-none text-dark">
                    <div class="card game-card h-100 shadow-sm text-center">
                        <img src="https://via.placeholder.com/300x400?text=Mobile+Legends" class="card-img-top" alt="MLBB">
                        <div class="card-body">
                            <h6 class="card-title fw-bold">Mobile Legends</h6>
                            <p class="text-muted small">Moonton</p>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-6 col-md-4 col-lg-3">
                <div class="card game-card h-100 shadow-sm text-center">
                    <img src="https://via.placeholder.com/300x400?text=Free+Fire" class="card-img-top" alt="FF">
                    <div class="card-body">
                        <h6 class="card-title fw-bold">Free Fire</h6>
                        <p class="text-muted small">Garena</p>
                    </div>
                </div>
            </div>

            <div class="col-6 col-md-4 col-lg-3">
                <div class="card game-card h-100 shadow-sm text-center">
                    <img src="https://via.placeholder.com/300x400?text=Valorant" class="card-img-top" alt="VAL">
                    <div class="card-body">
                        <h6 class="card-title fw-bold">Valorant</h6>
                        <p class="text-muted small">Riot Games</p>
                    </div>
                </div>
            </div>

            <div class="col-6 col-md-4 col-lg-3">
                <div class="card game-card h-100 shadow-sm text-center">
                    <img src="https://via.placeholder.com/300x400?text=Genshin+Impact" class="card-img-top" alt="GI">
                    <div class="card-body">
                        <h6 class="card-title fw-bold">Genshin Impact</h6>
                        <p class="text-muted small">HoYoverse</p>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer class="bg-dark text-white text-center py-4 mt-5">
        <div class="container">
            <p class="mb-0">&copy; 2026 Kelompok 3 - Software Engineering Project</p>
            <small class="text-muted">Dibuat dengan Laravel & Laragon</small>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>