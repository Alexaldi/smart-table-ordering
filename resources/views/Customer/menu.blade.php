<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Menu - Coffee Shop</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }

        .cafe-header {
            height: 58vh;
            position: relative;
            overflow: hidden;
        }

        .cafe-header .img {
            position: absolute;
            top: 15px;
            left: 15px;
            right: 15px;
            bottom: 15px;
            z-index: 1;
            border-radius: 20px;
            overflow: hidden;
            background:
                linear-gradient(135deg, rgba(45, 31, 24, 0.08), rgba(212, 165, 116, 0.18)),
                url('https://images.unsplash.com/photo-1442512595331-e89e73853f31?auto=format&fit=crop&w=1400&q=80') center 78% / cover;
        }

        .cafe-header .container-fluid {
            height: 100%;
            position: relative;
            z-index: 2;
        }

        .cafe-header .container-fluid > .icon-container {
            position: absolute;
            top: 30px;
            right: 30px;
            z-index: 3;
        }

        .btn-icon {
            background-color: #ffffff !important;
            border: none;
            border-radius: 50%;
            width: 42px;
            height: 42px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
            transition: all 0.3s ease;
            text-decoration: none;
            color: #333333;
        }

        .btn-icon:hover {
            background-color: #f8f9fa !important;
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
            color: #000000;
        }

        .btn-icon i {
            font-size: 1.1rem;
        }

        .badge-cart {
            position: absolute;
            top: -4px;
            right: -4px;
            background-color: #e74c3c;
            color: white;
            border-radius: 50%;
            min-width: 20px;
            height: 20px;
            padding: 0 5px;
            font-size: 0.7rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            border: 2px solid #ffffff;
        }

        .card-tempat {
            background-color: #f9f9f9;
            border: 1px solid #cdcdcd;
            border-radius: 20px 20px 0 0;
            padding: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: all 0.3s ease;
        }

        .card-info h4 {
            margin-bottom: 5px;
            font-weight: 600;
            font-size: 1.1rem;
            color: #333333;
        }

        .card-info p {
            margin-bottom: 0;
            color: #9b9b9b;
            font-size: 0.8rem;
        }

        .card-info span {
            color: #d4a574;
            font-weight: 600;
        }

        .card-arrow {
            color: #6c757d;
            font-size: 1.5rem;
            transition: all 0.3s ease;
        }

        .card-tempat:hover .card-arrow {
            color: #333333;
            transform: translateX(5px);
        }

        .sticky-card-tabel {
            position: sticky;
            top: 0;
            z-index: 101;
        }

        .card-tabel {
            background-color: #47444a;
            border: 1px solid #cdcdcd;
            border-radius: 0 0 20px 20px;
            padding: 7px;
            margin-bottom: 30px;
            transition: border-radius 0.2s;
        }

        .card-tabel.is-stuck {
            border-radius: 0;
        }

        .card-tabel h4 {
            color: #ffffff;
            margin-bottom: 0;
            font-weight: 600;
            font-size: 1.1rem;
        }

        .category-wrapper {
            background: #f9f9f9;
            border-bottom: 1px solid #d6d6d6;
            position: sticky;
            top: 36px;
            z-index: 100;
        }

        .category-list {
            display: flex;
            gap: 0;
            overflow-x: auto;
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        .category-list::-webkit-scrollbar {
            display: none;
        }

        .cat-btn {
            flex-shrink: 0;
            padding: 16px 24px;
            border-bottom: 2px solid transparent;
            background: transparent;
            border-radius: 0;
            font-size: 14px;
            font-weight: 500;
            color: #666;
            transition: all 0.2s ease;
            text-decoration: none;
            display: inline-block;
        }

        .cat-btn:hover,
        .cat-btn.active {
            color: #222;
            background: #f9f9f9;
        }

        .cat-btn.active {
            border-bottom-color: #222;
            font-weight: 600;
        }

        .menu-section {
            background: #f9f9f9;
            min-height: 40vh;
        }

        .discount-section {
            background: #fff;
            border-bottom: 1px solid #ececec;
            padding: 24px 0 8px;
        }

        .discount-strip {
            display: grid;
            grid-auto-flow: column;
            grid-auto-columns: minmax(245px, 285px);
            gap: 14px;
            overflow-x: auto;
            padding: 2px 0 18px;
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        .discount-strip::-webkit-scrollbar {
            display: none;
        }

        .discount-card {
            display: grid;
            grid-template-columns: 86px 1fr;
            gap: 12px;
            align-items: stretch;
            border: 1px solid #eadfd2;
            border-radius: 12px;
            background: linear-gradient(135deg, #fffaf4, #fff);
            padding: 10px;
            box-shadow: 0 8px 20px rgba(80, 54, 30, 0.07);
        }

        .discount-thumb {
            position: relative;
            border-radius: 10px;
            overflow: hidden;
            background: #f6eee4;
            min-height: 104px;
        }

        .discount-thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .discount-info {
            min-width: 0;
            display: flex;
            flex-direction: column;
        }

        .discount-title {
            color: #222;
            font-size: 14px;
            font-weight: 700;
            margin-bottom: 3px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .discount-meta {
            color: #777;
            font-size: 11px;
            margin-bottom: 6px;
        }

        .discount-price-row {
            line-height: 1.25;
            margin-bottom: 8px;
        }

        .discount-add {
            margin-top: auto;
            width: 100%;
            border: 1px solid #d4a574;
            border-radius: 8px;
            background: #d4a574;
            color: #fff;
            font-size: 12px;
            font-weight: 700;
            padding: 7px 10px;
            transition: all 0.2s;
        }

        .discount-add:hover {
            background: #c49464;
            border-color: #c49464;
        }

        .category-header-section {
            display: flex;
            align-items: center;
            gap: 16px;
            padding-top: 8px;
            margin-bottom: 20px;
        }

        .category-name {
            font-size: 22px;
            font-weight: 700;
            color: #222;
            margin-bottom: 0;
            white-space: nowrap;
        }

        .category-line {
            flex-grow: 1;
            height: 1px;
            background-color: #e0e0e0;
            min-width: 50px;
        }

        .menu-card {
            background: #fff;
            border: 1px solid #eee;
            border-radius: 12px;
            overflow: hidden;
            height: 100%;
            display: flex;
            flex-direction: column;
            transition: border-color 0.2s, transform 0.2s, box-shadow 0.2s;
        }

        .menu-card:hover {
            border-color: #d8d8d8;
            transform: translateY(-2px);
            box-shadow: 0 10px 24px rgba(35, 25, 18, 0.08);
        }

        .menu-img {
            width: 100%;
            aspect-ratio: 4 / 3;
            overflow: hidden;
            position: relative;
            background: #f5f5f5;
        }

        .menu-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .placeholder-visual {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            gap: 6px;
            background: linear-gradient(135deg, #f6eee4, #ffffff);
            color: #b58b5a;
            text-align: center;
        }

        .placeholder-visual i {
            font-size: 2rem;
        }

        .placeholder-visual span {
            font-size: 12px;
            font-weight: 600;
        }

        .menu-body {
            padding: 12px;
            display: flex;
            flex-direction: column;
            flex-grow: 1;
        }

        .menu-title {
            font-size: 14px;
            font-weight: 600;
            color: #222;
            margin-bottom: 4px;
            line-height: 1.4;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .menu-desc {
            font-size: 12px;
            color: #777;
            line-height: 1.5;
            margin-bottom: 10px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            min-height: 36px;
        }

        .menu-price {
            font-size: 14px;
            font-weight: 700;
            color: #111;
            margin-bottom: 5px;
        }

        .menu-price-wrapper {
            margin-bottom: 5px;
        }

        .original-price {
            display: inline-block;
            color: #999;
            font-size: 12px;
            text-decoration: line-through;
            margin-right: 6px;
        }

        .discounted-price {
            display: inline-block;
            color: #d4a574;
            font-size: 14px;
            font-weight: 700;
        }

        .discount-badge {
            position: absolute;
            top: 8px;
            left: 8px;
            background: #ff4757;
            color: #fff;
            padding: 4px 9px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 700;
            z-index: 2;
        }

        .modal-discount-row {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .modal-discount-row .badge {
            background: #ff4757;
            color: #fff;
        }

        .stock {
            font-size: 11px;
            color: #6d6d6d;
            margin-bottom: 12px;
        }

        .btn-tambah {
            margin-top: auto;
            width: 100%;
            padding: 8px;
            background: #fff;
            border: 1px solid #d4a574;
            color: #d4a574;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-tambah:hover {
            background: #d4a574;
            color: #fff;
        }

        .modal-content {
            border-radius: 5px 5px 12px 12px;
            overflow: hidden;
        }

        .modal-image-wrapper {
            width: 100%;
            height: 240px;
            line-height: 0;
            background: #f6eee4;
        }

        .modal-image-wrapper img {
            width: 100%;
            height: 100%;
            display: block;
            object-fit: cover;
        }

        .btn-close-modal {
            position: absolute;
            top: 12px;
            right: 12px;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.9);
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 10;
            transition: all 0.2s;
        }

        .btn-close-modal:hover {
            background: #fff;
            transform: scale(1.1);
        }

        .btn-close-modal i {
            font-size: 14px;
            color: #222;
        }

        .qty-btn {
            width: 34px;
            height: 34px;
            padding: 0;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .title-button {
            font-size: 14px;
            background: #222;
            border-color: #222;
        }

        .title-button:hover {
            background: #d4a574;
            border-color: #d4a574;
        }

        .alert-floating {
            border-radius: 12px;
            border: none;
            box-shadow: 0 10px 24px rgba(35, 25, 18, 0.08);
        }

        .empty-state {
            background: #fff;
            border-radius: 16px;
            padding: 38px 24px;
            text-align: center;
            color: #777;
            border: 1px solid #eee;
        }

        .empty-state i {
            display: block;
            font-size: 42px;
            color: #d4a574;
            margin-bottom: 12px;
        }

        @media (min-width: 768px) {
            .modal-dialog {
                max-width: 450px;
            }
        }

        @media (max-width: 576px) {
            .cafe-header {
                height: 46vh;
            }

            .cafe-header .container-fluid > .icon-container {
                top: 28px;
                right: 22px;
            }

            .category-wrapper {
                top: 34px;
            }

            .discount-strip {
                grid-auto-columns: minmax(232px, 82vw);
            }

            .cat-btn {
                padding: 14px 18px;
                font-size: 13px;
            }

            .menu-title,
            .menu-price {
                font-size: 13px;
            }

            .btn-tambah {
                padding: 7px;
                font-size: 12px;
            }

            .category-name {
                font-size: 20px;
            }

            .modal-image-wrapper {
                height: 210px;
            }
        }
    </style>
</head>

<body>
    <header class="cafe-header">
        <div class="img" aria-hidden="true"></div>

        <div class="container-fluid px-3 py-2 d-flex align-items-center justify-content-between">
            <div></div>

            <div class="d-flex gap-2 align-items-center icon-container">
                <a href="{{ route('customer-menu.search', ['token' => $token]) }}" class="btn btn-icon" aria-label="Cari menu">
                    <i class="bi bi-search"></i>
                </a>
                <a href="{{ route('customer-menu.cart', ['token' => $token]) }}" class="btn btn-icon position-relative" aria-label="Keranjang">
                    <i class="bi bi-bag"></i>
                    @if ($cartCount > 0)
                        <span class="badge-cart">{{ $cartCount }}</span>
                    @endif
                </a>
            </div>
        </div>
    </header>

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card-tempat">
                    <div class="card-info">
                        <h4>Coffee Shop Coffee Coffeean</h4>
                        <p>Open Today, <span>8:00 AM - 10:00 PM</span></p>
                    </div>

                    <div class="card-arrow">
                        <i class="bi bi-arrow-right"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="tabelSentinel"></div>
    <div class="sticky-card-tabel">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card-tabel" id="cardTabel">
                        <h4 class="text-center">Nomor Meja: <span>{{ $table->table_number }}</span></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if (session('success'))
        <div class="container">
            <div class="alert alert-success alert-floating">{{ session('success') }}</div>
        </div>
    @endif

    @if (session('error'))
        <div class="container">
            <div class="alert alert-danger alert-floating">{{ session('error') }}</div>
        </div>
    @endif

    <div class="category-wrapper">
        <div class="container">
            <div class="category-list">
                @if (($discountedMenuItems ?? collect())->count() > 0)
                    <a href="#discount-menu" class="cat-btn">Menu Diskon</a>
                @endif
                <a href="#all-menu" class="cat-btn active">Semua Menu</a>
                @foreach ($categories as $category)
                    @if ($category->menuItems->count() > 0)
                        <a href="#category-{{ $category->id }}" class="cat-btn">{{ $category->name }}</a>
                    @endif
                @endforeach
            </div>
        </div>
    </div>

    @if (($discountedMenuItems ?? collect())->count() > 0)
        <section class="discount-section" id="discount-menu">
            <div class="container">
                <div class="category-header-section">
                    <h3 class="category-name">Menu Sedang Diskon</h3>
                    <div class="category-line"></div>
                </div>

                <div class="discount-strip">
                    @foreach ($discountedMenuItems as $promoItem)
                        @php
                            $imageUrl = $promoItem->image_url;

                            if ($imageUrl && \Illuminate\Support\Str::startsWith($imageUrl, ['http://', 'https://'])) {
                                $imageSrc = $imageUrl;
                            } elseif ($imageUrl && \Illuminate\Support\Str::startsWith($imageUrl, 'storage/')) {
                                $imageSrc = asset($imageUrl);
                            } elseif ($imageUrl) {
                                $imageSrc = asset('storage/' . $imageUrl);
                            } else {
                                $imageSrc = null;
                            }

                            $activeDiscount = $promoItem->activeDiscount();
                            $discountPercentage = (float) $activeDiscount->percentage;
                            $discountAmount = $promoItem->discountAmount();
                            $finalPrice = $promoItem->finalPrice();
                        @endphp

                        <article class="discount-card">
                            <div class="discount-thumb">
                                <span class="discount-badge">{{ rtrim(rtrim(number_format($discountPercentage, 2, ',', '.'), '0'), ',') }}% OFF</span>
                                @if ($imageSrc)
                                    <img src="{{ $imageSrc }}" alt="{{ $promoItem->name }}">
                                @else
                                    <div class="placeholder-visual">
                                        <i class="bi bi-cup-hot"></i>
                                    </div>
                                @endif
                            </div>

                            <div class="discount-info">
                                <div class="discount-title">{{ $promoItem->name }}</div>
                                <div class="discount-meta">Hemat Rp{{ number_format($discountAmount, 0, ',', '.') }} per item</div>
                                <div class="discount-price-row">
                                    <span class="original-price">Rp{{ number_format($promoItem->price, 0, ',', '.') }}</span><br>
                                    <span class="discounted-price">Rp{{ number_format($finalPrice, 0, ',', '.') }}</span>
                                </div>
                                <button class="discount-add" type="button" data-bs-toggle="modal" data-bs-target="#menuModal-{{ $promoItem->id }}">
                                    <i class="bi bi-plus-lg"></i> Tambah
                                </button>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <main class="menu-section" id="all-menu">
        <div class="container py-4">
            @php
                $hasMenu = false;
            @endphp

            @foreach ($categories as $category)
                @if ($category->menuItems->count() > 0)
                    @php
                        $hasMenu = true;
                    @endphp

                    <section id="category-{{ $category->id }}" class="mb-5">
                        <div class="category-header-section">
                            <h3 class="category-name">{{ $category->name }}</h3>
                            <div class="category-line"></div>
                        </div>

                        <div class="row g-3">
                            @foreach ($category->menuItems as $menuItem)
                                @php
                                    $imageUrl = $menuItem->image_url;

                                    if ($imageUrl && \Illuminate\Support\Str::startsWith($imageUrl, ['http://', 'https://'])) {
                                        $imageSrc = $imageUrl;
                                    } elseif ($imageUrl && \Illuminate\Support\Str::startsWith($imageUrl, 'storage/')) {
                                        $imageSrc = asset($imageUrl);
                                    } elseif ($imageUrl) {
                                        $imageSrc = asset('storage/' . $imageUrl);
                                    } else {
                                        $imageSrc = null;
                                    }

                                    $activeDiscount = $menuItem->activeDiscount();
                                    $hasDiscount = $activeDiscount !== null;
                                    $discountPercentage = $hasDiscount ? (float) $activeDiscount->percentage : 0;
                                    $discountAmount = $menuItem->discountAmount();
                                    $finalPrice = $menuItem->finalPrice();
                                @endphp

                                <div class="col-6 col-md-4 col-lg-3">
                                    <article class="menu-card">
                                        <div class="menu-img">
                                            @if ($hasDiscount)
                                                <span class="discount-badge">{{ rtrim(rtrim(number_format($discountPercentage, 2, ',', '.'), '0'), ',') }}% OFF</span>
                                            @endif

                                            @if ($imageSrc)
                                                <img src="{{ $imageSrc }}" alt="{{ $menuItem->name }}">
                                            @else
                                                <div class="placeholder-visual">
                                                    <i class="bi bi-cup-hot"></i>
                                                    <span>{{ $menuItem->name }}</span>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="menu-body">
                                            <h6 class="menu-title">{{ $menuItem->name }}</h6>
                                            <p class="menu-desc">{{ $menuItem->description ?: 'Menu favorit coffee shop kami.' }}</p>
                                            @if ($hasDiscount)
                                                <div class="menu-price-wrapper">
                                                    <span class="original-price">Rp{{ number_format($menuItem->price, 0, ',', '.') }}</span>
                                                    <span class="discounted-price">Rp{{ number_format($finalPrice, 0, ',', '.') }}</span>
                                                </div>
                                            @else
                                                <p class="menu-price">Rp{{ number_format($menuItem->price, 0, ',', '.') }}</p>
                                            @endif
                                            <div class="stock">Stock {{ $menuItem->stock }}</div>
                                            <button class="btn-tambah" type="button" data-bs-toggle="modal" data-bs-target="#menuModal-{{ $menuItem->id }}">
                                                <i class="bi bi-plus-lg"></i> Tambah
                                            </button>
                                        </div>
                                    </article>
                                </div>

                                <div class="modal fade" id="menuModal-{{ $menuItem->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content border-0">
                                            <form action="{{ route('customer-menu.cart.add', ['token' => $token]) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="menu_item_id" value="{{ $menuItem->id }}">
                                                <input type="hidden" name="quantity" id="quantityInput-{{ $menuItem->id }}" value="1">

                                                <div class="modal-body p-0">
                                                    <div class="modal-image-wrapper position-relative">
                                                        @if ($imageSrc)
                                                            <img src="{{ $imageSrc }}" class="img-fluid w-100" alt="{{ $menuItem->name }}">
                                                        @else
                                                            <div class="placeholder-visual">
                                                                <i class="bi bi-cup-hot"></i>
                                                                <span>{{ $menuItem->name }}</span>
                                                            </div>
                                                        @endif

                                                        <button type="button" class="btn-close-modal" data-bs-dismiss="modal" aria-label="Tutup">
                                                            <i class="bi bi-x-lg"></i>
                                                        </button>
                                                    </div>

                                                    <div class="p-4">
                                                        <h5 class="fw-bold mb-1">{{ $menuItem->name }}</h5>
                                                        <p class="text-muted small mb-1">{{ $category->name }}</p>
                                                        @if ($hasDiscount)
                                                            <div class="modal-discount-row mb-1">
                                                                <span class="original-price">Rp{{ number_format($menuItem->price, 0, ',', '.') }}</span>
                                                                <span class="fw-bold" style="color: #d4a574;">Rp{{ number_format($finalPrice, 0, ',', '.') }}</span>
                                                                <span class="badge">{{ rtrim(rtrim(number_format($discountPercentage, 2, ',', '.'), '0'), ',') }}% OFF</span>
                                                            </div>
                                                            <p class="text-muted small mb-2">Hemat Rp{{ number_format($discountAmount, 0, ',', '.') }} per item</p>
                                                        @else
                                                            <p class="fw-bold mb-2" style="color: #d4a574;">Rp{{ number_format($menuItem->price, 0, ',', '.') }}</p>
                                                        @endif
                                                        <p class="text-muted small mb-3">{{ $menuItem->description ?: 'Menu favorit coffee shop kami.' }}</p>

                                                        <div class="mb-3">
                                                            <label for="notes-{{ $menuItem->id }}" class="form-label small fw-bold text-uppercase">Catatan</label>
                                                            <textarea id="notes-{{ $menuItem->id }}" name="notes" class="form-control" rows="2" placeholder="Opsional, contoh: less sugar"></textarea>
                                                        </div>

                                                        <hr>

                                                        <div class="d-flex justify-content-between align-items-center mb-4">
                                                            <label class="fw-bold mb-0">Total Order</label>
                                                            <div class="d-flex align-items-center gap-3">
                                                                <button class="btn btn-outline-secondary btn-sm qty-btn" type="button" data-qty-action="minus" data-target-id="{{ $menuItem->id }}" aria-label="Kurangi">
                                                                    <i class="bi bi-dash"></i>
                                                                </button>
                                                                <span class="fw-bold" id="qtyValue-{{ $menuItem->id }}">1</span>
                                                                <button class="btn btn-outline-secondary btn-sm qty-btn" type="button" data-qty-action="plus" data-target-id="{{ $menuItem->id }}" data-max="{{ $menuItem->stock }}" aria-label="Tambah">
                                                                    <i class="bi bi-plus"></i>
                                                                </button>
                                                            </div>
                                                        </div>

                                                        <div class="d-grid gap-2">
                                                            <button class="btn btn-dark btn-lg title-button" type="submit">Tambah ke Keranjang</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </section>
                @endif
            @endforeach

            @if (!$hasMenu)
                <div class="empty-state">
                    <i class="bi bi-cup-hot"></i>
                    <h5 class="fw-bold mb-2">Belum ada menu tersedia</h5>
                    <p class="mb-0">Silakan panggil staf untuk pilihan menu hari ini.</p>
                </div>
            @endif
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.querySelectorAll('[data-qty-action]').forEach((button) => {
            button.addEventListener('click', () => {
                const menuId = button.dataset.targetId;
                const action = button.dataset.qtyAction;
                const max = Number(button.dataset.max || 999);
                const qtyValue = document.getElementById(`qtyValue-${menuId}`);
                const qtyInput = document.getElementById(`quantityInput-${menuId}`);
                let qty = Number(qtyInput.value || 1);

                if (action === 'minus' && qty > 1) {
                    qty -= 1;
                }

                if (action === 'plus' && qty < max) {
                    qty += 1;
                }

                qtyInput.value = qty;
                qtyValue.textContent = qty;
            });
        });

        const sentinel = document.getElementById('tabelSentinel');
        const cardTabel = document.getElementById('cardTabel');

        if (sentinel && cardTabel) {
            const observer = new IntersectionObserver(([entry]) => {
                cardTabel.classList.toggle('is-stuck', !entry.isIntersecting);
            }, { threshold: 0 });

            observer.observe(sentinel);
        }

        const categoryButtons = document.querySelectorAll('.cat-btn');
        categoryButtons.forEach((button) => {
            button.addEventListener('click', () => {
                categoryButtons.forEach((item) => item.classList.remove('active'));
                button.classList.add('active');
            });
        });
    </script>
</body>

</html>
