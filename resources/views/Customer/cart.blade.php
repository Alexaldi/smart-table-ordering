<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Saya</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('customer/assets/css/cart.css') }}">
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
                <form id="checkoutForm" action="{{ route('customer-menu.checkout', ['token' => $token]) }}" method="POST">
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


                    <input type="text" name="customer_name" class="form-control mb-2" placeholder="Nama kamu" required>
                    <input type="tel" name="customer_phone" class="form-control mb-2" placeholder="Nomor telepon" required>
                    <input type="email" name="customer_email" class="form-control mb-2" placeholder="Email untuk receipt" required>
                    <textarea name="notes" class="form-control order-notes" rows="2" placeholder="Catatan pesanan, contoh: antar kalau semua sudah siap"></textarea>

                    <button class="btn-checkout" type="submit" id="checkoutButton">
                        <span id="checkoutButtonText">Pesan Sekarang</span>
                    </button>
                </form>
            </div>
        </div>
    @endif

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ $clientKey }}"></script>
    <script>
        const checkoutForm = document.getElementById('checkoutForm');

        if (checkoutForm) {
            checkoutForm.addEventListener('submit', async function (event) {
                event.preventDefault();

                const button = document.getElementById('checkoutButton');
                const buttonText = document.getElementById('checkoutButtonText');
                button.disabled = true;
                buttonText.textContent = 'Memproses...';

                const formData = new FormData(checkoutForm);

                try {
                    const response = await fetch(checkoutForm.action, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        body: formData,
                    });

                    const data = await response.json();

                    if (!response.ok) {
                        alert(data.message || 'Checkout gagal, silakan coba lagi.');
                        button.disabled = false;
                        buttonText.textContent = 'Pesan Sekarang';
                        return;
                    }

                    snap.pay(data.snap_token, {
                        onSuccess: function (result) {
                            window.location.href = data.summary_url;
                        },
                        onPending: function (result) {
                            alert('Pembayaran belum selesai. Silakan selesaikan pembayaran terlebih dahulu.');
                            button.disabled = false;
                            buttonText.textContent = 'Pesan Sekarang';
                        },
                        onError: function (result) {
                            alert('Pembayaran gagal, silakan coba lagi.');
                            button.disabled = false;
                            buttonText.textContent = 'Pesan Sekarang';
                        },
                        onClose: function () {
                            button.disabled = false;
                            buttonText.textContent = 'Pesan Sekarang';
                        }
                    });
                } catch (error) {
                    alert('Terjadi kesalahan koneksi, silakan coba lagi.');
                    button.disabled = false;
                    buttonText.textContent = 'Pesan Sekarang';
                }
            });
        }
    </script>
</body>

</html>
