<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Saya</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f9f9f9;
            padding-bottom: 255px;
            margin: 0;
        }

        body.cart-empty {
            padding-bottom: 0;
        }

        .cart-header {
            background: #fff;
            border-bottom: 1px solid #e8e8e8;
            padding: 16px 0;
            z-index: 1000;
        }

        .back-link {
            color: #666;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: color 0.2s;
        }

        .back-link:hover {
            color: #222;
        }

        .back-link i {
            font-size: 16px;
        }

        .cart-content {
            background: #fff;
            min-height: calc(100vh - 250px);
        }

        .cart-items {
            padding: 20px 0;
        }

        .cart-item {
            display: flex;
            gap: 16px;
            padding: 20px 0;
            border-bottom: 1px solid #f0f0f0;
            align-items: flex-start;
        }

        .cart-item:last-child {
            border-bottom: none;
        }

        .item-image {
            flex-shrink: 0;
            width: 80px;
            height: 80px;
            border-radius: 10px;
            overflow: hidden;
            background: #f5f5f5;
        }

        .item-image img {
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
            gap: 4px;
            background: linear-gradient(135deg, #f6eee4, #ffffff);
            color: #b58b5a;
            text-align: center;
        }

        .placeholder-visual i {
            font-size: 1.45rem;
        }

        .placeholder-visual span {
            font-size: 10px;
            font-weight: 600;
            padding: 0 5px;
        }

        .item-details {
            flex-grow: 1;
            min-width: 0;
        }

        .item-name {
            font-size: 15px;
            font-weight: 700;
            color: #222;
            margin-bottom: 4px;
            text-transform: uppercase;
        }

        .item-note {
            font-style: italic;
            color: #777;
            font-size: 12px;
            margin-bottom: 8px;
        }

        .item-price {
            font-size: 14px;
            font-weight: 700;
            color: #222;
            margin-top: 6px;
        }

        .item-price .original-price {
            color: #999;
            text-decoration: line-through;
            font-weight: 500;
            margin-right: 6px;
        }

        .item-price .discounted-price {
            color: #222;
            font-weight: 700;
        }

        .item-discount {
            color: #ff4757;
            font-size: 12px;
            font-weight: 600;
            margin-top: 4px;
        }

        .discount-chip {
            display: inline-flex;
            align-items: center;
            padding: 2px 7px;
            border-radius: 999px;
            background: #fff0f1;
            color: #ff4757;
            font-size: 11px;
            font-weight: 700;
            margin-bottom: 6px;
        }

        .item-subtotal {
            color: #d4a574;
            font-size: 13px;
            font-weight: 700;
        }

        .item-actions {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 12px;
        }

        .quantity-control {
            display: flex;
            align-items: center;
            gap: 12px;
            background: #f5f5f5;
            border-radius: 8px;
            padding: 4px;
        }

        .qty-btn {
            width: 28px;
            height: 28px;
            border: none;
            background: #fff;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
            color: #222;
            font-size: 12px;
        }

        .qty-btn:hover:not(:disabled) {
            background: #e8e8e8;
        }

        .qty-btn:disabled {
            color: #c7c7c7;
            cursor: not-allowed;
        }

        .qty-value {
            font-size: 14px;
            font-weight: 600;
            min-width: 24px;
            text-align: center;
        }

        .remove-btn {
            background: none;
            border: none;
            color: #999;
            cursor: pointer;
            font-size: 18px;
            transition: all 0.2s;
            padding: 4px;
        }

        .remove-btn:hover {
            color: #ff4757;
        }

        .empty-cart {
            min-height: 420px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        .empty-icon i {
            opacity: 0.35;
            color: #d4a574;
        }

        .empty-cart h5 {
            color: #222;
        }

        .btn-browse {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 24px;
            background: #222;
            color: #fff;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.2s;
        }

        .btn-browse:hover {
            background: #d4a574;
            color: #fff;
        }

        .cart-footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: #fff;
            border-top: 1px solid #e8e8e8;
            padding: 16px 0;
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.05);
            z-index: 1000;
        }

        .cart-summary {
            margin-bottom: 12px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .summary-row.total {
            font-size: 16px;
            font-weight: 700;
            margin-top: 8px;
        }

        .summary-price {
            color: #666;
        }

        .total-price {
            color: #222;
            font-size: 18px;
        }

        .summary-divider {
            height: 1px;
            background: #f0f0f0;
            margin: 12px 0;
        }

        .order-notes {
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            font-size: 13px;
            resize: none;
            margin-bottom: 12px;
        }

        .order-notes:focus {
            border-color: #d4a574;
            box-shadow: 0 0 0 3px rgba(212, 165, 116, 0.18);
        }

        .btn-checkout {
            width: 100%;
            padding: 14px;
            background: #d4a574;
            border: none;
            border-radius: 10px;
            color: #fff;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-checkout:hover {
            background: #c49464;
        }

        .alert-soft {
            border: none;
            border-radius: 12px;
            margin: 16px 0 0;
        }

        @media (max-width: 576px) {
            body {
                padding-bottom: 245px;
            }

            .cart-header {
                padding: 12px 0;
            }

            .cart-item {
                gap: 12px;
                padding: 16px 0;
            }

            .item-image {
                width: 70px;
                height: 70px;
            }

            .item-name {
                font-size: 14px;
            }

            .item-note {
                font-size: 12px;
            }

            .item-price {
                font-size: 14px;
            }

            .quantity-control {
                gap: 10px;
            }

            .qty-btn {
                width: 26px;
                height: 26px;
            }

            .cart-footer {
                padding: 12px 0;
            }

            .summary-row {
                font-size: 13px;
            }

            .total-price {
                font-size: 16px;
            }

            .btn-checkout {
                padding: 12px;
                font-size: 15px;
            }
        }

        @media (min-width: 768px) {
            .cart-content .container,
            .cart-footer .container,
            .cart-header .container {
                max-width: 700px;
            }

            .item-image {
                width: 90px;
                height: 90px;
            }

            .quantity-control {
                gap: 14px;
            }

            .qty-btn {
                width: 32px;
                height: 32px;
            }
        }
    </style>
</head>

<body @if (empty($cart)) class="cart-empty" @endif>
    <header class="cart-header sticky-top">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between">
                <h5 class="mb-0 fw-bold">Keranjang Saya</h5>
                <a href="{{ route('customer-menu.index', ['token' => $token]) }}" class="back-link">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </header>

    <main class="cart-content">
        <div class="container">
            @if (session('success'))
                <div class="alert alert-success alert-soft">{{ session('success') }}</div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-soft">{{ session('error') }}</div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger alert-soft">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            @if (!empty($cart))
                <div class="cart-items">
                    @foreach ($cart as $item)
                        @php
                            $imageUrl = $item['image_url'] ?? null;

                            if ($imageUrl && \Illuminate\Support\Str::startsWith($imageUrl, ['http://', 'https://'])) {
                                $imageSrc = $imageUrl;
                            } elseif ($imageUrl && \Illuminate\Support\Str::startsWith($imageUrl, 'storage/')) {
                                $imageSrc = asset($imageUrl);
                            } elseif ($imageUrl) {
                                $imageSrc = asset('storage/' . $imageUrl);
                            } else {
                                $imageSrc = null;
                            }

                            $quantity = (int) ($item['quantity'] ?? 1);
                            $originalPrice = (float) ($item['original_price'] ?? $item['price'] ?? 0);
                            $finalPrice = (float) ($item['price'] ?? $originalPrice);
                            $discountPercentage = (float) ($item['discount_percentage'] ?? 0);
                            $discountAmount = (float) ($item['discount_amount'] ?? 0);
                            $hasDiscount = $discountPercentage > 0 && $discountAmount > 0;
                        @endphp

                        <article class="cart-item">
                            <div class="item-image">
                                @if ($imageSrc)
                                    <img src="{{ $imageSrc }}" alt="{{ $item['name'] }}">
                                @else
                                    <div class="placeholder-visual">
                                        <i class="bi bi-cup-hot"></i>
                                        <span>{{ $item['name'] }}</span>
                                    </div>
                                @endif
                            </div>

                            <div class="item-details">
                                <h6 class="item-name">{{ $item['name'] }}</h6>
                                @if ($hasDiscount)
                                    <span class="discount-chip">{{ rtrim(rtrim(number_format($discountPercentage, 2, ',', '.'), '0'), ',') }}% OFF</span>
                                @endif
                                <p class="item-note">
                                    @if (!empty($item['notes']))
                                        {{ $item['notes'] }}
                                    @else
                                        Tanpa catatan
                                    @endif
                                </p>
                                <div class="item-price">
                                    Harga satuan:
                                    @if ($hasDiscount)
                                        <span class="original-price">Rp{{ number_format($originalPrice, 0, ',', '.') }}</span>
                                        <span class="discounted-price">Rp{{ number_format($finalPrice, 0, ',', '.') }}</span>
                                        <div class="item-discount">Hemat Rp{{ number_format($discountAmount * $quantity, 0, ',', '.') }}</div>
                                    @else
                                        Rp{{ number_format($finalPrice, 0, ',', '.') }}
                                    @endif
                                </div>
                                <div class="item-subtotal">Subtotal: Rp{{ number_format($item['subtotal'], 0, ',', '.') }}</div>
                            </div>

                            <div class="item-actions">
                                <div class="quantity-control">
                                    <form action="{{ route('customer-menu.cart.update', ['token' => $token]) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="menu_item_id" value="{{ $item['menu_item_id'] }}">
                                        <input type="hidden" name="quantity" value="{{ max(1, $quantity - 1) }}">
                                        <button class="qty-btn" type="submit" @disabled($quantity <= 1) aria-label="Kurangi {{ $item['name'] }}">
                                            <i class="bi bi-dash"></i>
                                        </button>
                                    </form>

                                    <span class="qty-value">{{ $quantity }}</span>

                                    <form action="{{ route('customer-menu.cart.update', ['token' => $token]) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="menu_item_id" value="{{ $item['menu_item_id'] }}">
                                        <input type="hidden" name="quantity" value="{{ $quantity + 1 }}">
                                        <button class="qty-btn" type="submit" aria-label="Tambah {{ $item['name'] }}">
                                            <i class="bi bi-plus"></i>
                                        </button>
                                    </form>
                                </div>

                                <form action="{{ route('customer-menu.cart.remove', ['token' => $token]) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="menu_item_id" value="{{ $item['menu_item_id'] }}">
                                    <button class="remove-btn" type="submit" aria-label="Hapus {{ $item['name'] }}">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </article>
                    @endforeach
                </div>
            @else
                <div class="empty-cart py-5">
                    <div class="empty-icon mb-3">
                        <i class="bi bi-cart-x display-1"></i>
                    </div>
                    <h5 class="fw-bold mb-2">Keranjang Kosong</h5>
                    <p class="text-muted mb-4">Yuk, tambahin menu favorit kamu dari meja {{ $table->table_number }}.</p>
                    <a href="{{ route('customer-menu.index', ['token' => $token]) }}" class="btn-browse">
                        <i class="bi bi-search"></i> Lihat Menu
                    </a>
                </div>
            @endif
        </div>
    </main>

    @if (!empty($cart))
        <div class="cart-footer">
            <div class="container">
                <form action="{{ route('customer-menu.checkout', ['token' => $token]) }}" method="POST">
                    @csrf

                    <div class="cart-summary">
                        <div class="summary-row">
                            <span>Subtotal sebelum diskon (<span>{{ $cartCount }}</span> item)</span>
                            <span class="summary-price">Rp{{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="summary-row">
                            <span>Total Diskon</span>
                            <span class="summary-price">-Rp{{ number_format($discountTotal ?? 0, 0, ',', '.') }}</span>
                        </div>
                        <div class="summary-row">
                            <span>Biaya Layanan</span>
                            <span class="summary-price">Rp0</span>
                        </div>
                        <div class="summary-divider"></div>
                        <div class="summary-row total">
                            <span>Total</span>
                            <span class="total-price">Rp{{ number_format($grandTotal, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <textarea name="notes" class="form-control order-notes" rows="2" placeholder="Catatan pesanan, contoh: antar kalau semua sudah siap"></textarea>

                    <button class="btn-checkout" type="submit">Pesan Sekarang</button>
                </form>
            </div>
        </div>
    @endif

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
