<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Keranjang - Coffee Shop</title>

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet" />
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet" />

  <link rel="stylesheet" href="{{ asset('customer/assets/css/cart.css') }}" />
</head>
<body>

  <!-- Cart Header -->
  <header class="cart-header sticky-top">
    <div class="container">
      <div class="d-flex align-items-center justify-content-between">
        <h5 class="mb-0 fw-bold">Keranjang Saya</h5>
        <a href="{{ route('customer-menu.index') }}" class="back-link">
          <i class="bi bi-arrow-left"></i> Kembali
        </a>
      </div>
    </div>
  </header>

  <!-- Cart Content -->
  <div class="cart-content">
    <div class="container">
      
      <!-- Cart Items (Ada isi) -->
      <div class="cart-items" id="cartItems">
        
        <!-- Item 1 -->
        <div class="cart-item">
          <div class="item-image">
            <img src="img/espresso.webp" alt="Espresso">
          </div>
          <div class="item-details">
            <h6 class="item-name">Espresso</h6>
            <p class="item-variant">Regular • Es Normal</p>
            <p class="item-note text-muted small mb-2">Jangan terlalu manis</p>
            <div class="item-price">Rp18.000</div>
          </div>
          <div class="item-actions">
            <div class="quantity-control">
              <button class="qty-btn" onclick="decreaseQty(1)">
                <i class="bi bi-dash"></i>
              </button>
              <span class="qty-value">2</span>
              <button class="qty-btn" onclick="increaseQty(1)">
                <i class="bi bi-plus"></i>
              </button>
            </div>
            <button class="remove-btn" onclick="removeItem(1)">
              <i class="bi bi-trash"></i>
            </button>
          </div>
        </div>

        <!-- Item 2 -->
        <div class="cart-item">
          <div class="item-image">
            <img src="img/capucino.jpg" alt="Cappuccino">
          </div>
          <div class="item-details">
            <h6 class="item-name">Cappuccino</h6>
            <p class="item-variant">Large • Es Sedikit</p>
            <div class="item-price">Rp33.000</div>
          </div>
          <div class="item-actions">
            <div class="quantity-control">
              <button class="qty-btn" onclick="decreaseQty(2)">
                <i class="bi bi-dash"></i>
              </button>
              <span class="qty-value">1</span>
              <button class="qty-btn" onclick="increaseQty(2)">
                <i class="bi bi-plus"></i>
              </button>
            </div>
            <button class="remove-btn" onclick="removeItem(2)">
              <i class="bi bi-trash"></i>
            </button>
          </div>
        </div>

        <!-- Item 3 -->
        <div class="cart-item">
          <div class="item-image">
            <img src="img/caffee_latte.jpg" alt="Cafe Latte">
          </div>
          <div class="item-details">
            <h6 class="item-name">Cafe Latte</h6>
            <p class="item-variant">Regular • Tanpa Es</p>
            <div class="item-price">Rp30.000</div>
          </div>
          <div class="item-actions">
            <div class="quantity-control">
              <button class="qty-btn" onclick="decreaseQty(3)">
                <i class="bi bi-dash"></i>
              </button>
              <span class="qty-value">1</span>
              <button class="qty-btn" onclick="increaseQty(3)">
                <i class="bi bi-plus"></i>
              </button>
            </div>
            <button class="remove-btn" onclick="removeItem(3)">
              <i class="bi bi-trash"></i>
            </button>
          </div>
        </div>

      </div>

      <!-- Empty Cart (Tidak ada isi) -->
      <div class="empty-cart text-center py-5" id="emptyCart" style="display: none;">
        <div class="empty-icon mb-3">
          <i class="bi bi-cart-x display-1 text-muted"></i>
        </div>
        <h5 class="fw-bold mb-2">Keranjang Kosong</h5>
        <p class="text-muted mb-4">Yuk, tambahin menu favorit kamu!</p>
        <a href="{{ route('customer-menu.index') }}" class="btn-browse">
          <i class="bi bi-search"></i> Lihat Menu
        </a>
      </div>

    </div>
  </div>

  <!-- Cart Footer (Checkout) -->
  <div class="cart-footer" id="cartFooter">
    <div class="container">
      <div class="cart-summary">
        <div class="summary-row">
          <span>Subtotal (<span id="totalItems">4</span> item)</span>
          <span class="summary-price" id="subtotalPrice">Rp104.000</span>
        </div>
        <div class="summary-row">
          <span>Biaya Layanan</span>
          <span class="summary-price">Rp5.000</span>
        </div>
        <div class="summary-divider"></div>
        <div class="summary-row total">
          <span>Total</span>
          <span class="total-price" id="totalPrice">Rp109.000</span>
        </div>
      </div>
      <button class="btn-checkout">
        Pesan Sekarang
      </button>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="{{ asset('customer/js/cart.js') }}"></script>
</body>
</html>