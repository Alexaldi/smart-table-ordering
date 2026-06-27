<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>QR-Menu</title>

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet" />

  <link rel="stylesheet" href="{{ asset('customer/assets/css/style.css') }}" />
</head>
<body>

  <!-- STICKY HEADER -->
  <header class="cafe-header sticky-top">
    <div class="container-fluid px-3 py-2 d-flex align-items-center justify-content-between">
      
      <div class="img">
        <img src="{{ asset('customer/assets/images/bg_coffee.png') }}" alt="Logo Cafe" class="logo"/>
      </div>

      <div class="d-flex gap-2 align-items-center icon-container">
        <a href="{{ route('customer-menu.search') }}" class="btn btn-icon" aria-label="Cari menu">
          <i class="bi bi-search"></i>
        </a>
        <a href="{{ route('customer-menu.cart') }}" class="btn btn-icon position-relative" aria-label="Keranjang">
          <i class="bi bi-bag"></i>
          <span class="badge-cart" id="cartCount">0</span>
        </a>
      </div>
    </div>
  </header>

  <!-- start card tempat/jadwal -->
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <a href="#" class="text-decoration-none text-dark d-block">
          
          <div class="card-tempat">

            <div class="card-info">
              <h4>Coffee Shop Coffee Coffeean</h4>
              <p>Open Today, <span>8:00 AM - 10:00 PM</span></p>
            </div>

            <div class="card-arrow">
              <i class="bi bi-arrow-right"></i>
            </div>

          </div>
        </a>
      </div>
    </div>
  </div>
  <!-- end Card Tempat/Jadwal -->

  <!-- start card tabel -->
  <div id="tabelSentinel"></div>
  <div class="sticky-card-tabel">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          <div class="card-tabel" id="cardTabel">
            <h4 class="text-center">Nomor Meja: <span>1</span></h4>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- end card tabel -->

  <!-- Start Category Navigation -->
  <div class="category-wrapper">
    <div class="container">
      <div class="category-list">
        <a href="#" class="cat-btn active">Semua Menu</a>
        <a href="#" class="cat-btn">Coffee</a>
        <a href="#" class="cat-btn">Tea</a>
        <a href="#" class="cat-btn">Non-Coffee</a>
      </div>
    </div>
  </div>
  <!-- End Category Navigation -->

  <!-- Start Menu Section -->
  <div class="menu-section">
    <div class="container py-4">
      
      <!-- Start Category Header -->
      <div class="category-header-section mb-4">
        <h3 class="category-name">Coffee</h3>
        <div class="category-line"></div>
      </div>
      <!-- End Category Header -->
  
      <!-- Start Menu Cards Grid -->
      <div class="row g-3">
        
        <!-- Coffee Menu 1 -->
      <div class="col-6 col-md-4 col-lg-3">
        <div class="menu-card">
          <div class="menu-img">
            <img src="img/espresso.webp" alt="Espresso">
            <!-- Badge Diskon (opsional) -->
            <span class="discount-badge">-20%</span>
          </div>
          <div class="menu-body">
            <h6 class="menu-title">Espresso</h6>
            
            <!-- Harga dengan Diskon -->
            <div class="menu-price-wrapper">
              <span class="original-price">Rp22.500</span>
              <span class="discounted-price">Rp18.000</span>
            </div>
            
            <button class="btn-tambah" data-bs-toggle="modal" data-bs-target="#menuModal">
              <i class="bi bi-plus-lg"></i> Tambah
            </button>
          </div>
        </div>
      </div>
  
        <!-- Coffee Menu 2 -->
        <div class="col-6 col-md-4 col-lg-3">
          <div class="menu-card">
            <div class="menu-img">
              <img src="img/americano.jpg" alt="Americano">
            </div>
            <div class="menu-body">
              <h6 class="menu-title">Americano</h6>
              <p class="menu-price">Rp22.000</p>
              <button class="btn-tambah" data-bs-toggle="modal" data-bs-target="#menuModal">
                <i class="bi bi-plus-lg"></i> Tambah
              </button>
            </div>
          </div>
        </div>
  
        <!-- Coffee Menu 3 -->
        <div class="col-6 col-md-4 col-lg-3">
          <div class="menu-card">
            <div class="menu-img">
              <img src="img/capucino.jpg" alt="Cappuccino">
            </div>
            <div class="menu-body">
              <h6 class="menu-title">Cappuccino</h6>
              <p class="menu-price">Rp28.000</p>
              <button class="btn-tambah" data-bs-toggle="modal" data-bs-target="#menuModal">
                <i class="bi bi-plus-lg"></i> Tambah
              </button>
            </div>
          </div>
        </div>
  
        <!-- Coffee Menu 4 -->
        <div class="col-6 col-md-4 col-lg-3">
          <div class="menu-card">
            <div class="menu-img">
              <img src="img/caffee_latte.jpg" alt="Cafe Latte">
            </div>
            <div class="menu-body">
              <h6 class="menu-title">Cafe Latte</h6>
              <p class="menu-price">Rp30.000</p>
              <button class="btn-tambah" data-bs-toggle="modal" data-bs-target="#menuModal">
                <i class="bi bi-plus-lg"></i> Tambah
              </button>
            </div>
          </div>
        </div>
  
        <!-- Coffee Menu 5 -->
        <div class="col-6 col-md-4 col-lg-3">
          <div class="menu-card">
            <div class="menu-img">
              <img src="img/Mochaccino.jpg" alt="Mochaccino">
            </div>
            <div class="menu-body">
              <h6 class="menu-title">Mochaccino</h6>
              <p class="menu-price">Rp32.000</p>
              <button class="btn-tambah" data-bs-toggle="modal" data-bs-target="#menuModal">
                <i class="bi bi-plus-lg"></i> Tambah
              </button>
            </div>
          </div>
        </div>

        <div class="category-header-section mb-4">
        <h3 class="category-name">Tea</h3>
        <div class="category-line"></div>
      </div>

      <div class="col-6 col-md-4 col-lg-3">
        <div class="menu-card">
          <div class="menu-img">
            <img src="img/Lemon_Tea.jpg" alt="Lemon Tea">
          </div>
          <div class="menu-body">
            <h6 class="menu-title">Lemon Tea</h6>
            <p class="menu-price">Rp18.000</p>
            <button class="btn-tambah" data-bs-toggle="modal" data-bs-target="#menuModal">
              <i class="bi bi-plus-lg"></i> Tambah
            </button>
          </div>
        </div>
      </div>

      <div class="col-6 col-md-4 col-lg-3">
        <div class="menu-card">
          <div class="menu-img">
            <img src="img/Lychee_Tea.jpg" alt="Lychee Tea">
          </div>
          <div class="menu-body">
            <h6 class="menu-title">Lychee Tea</h6>
            <p class="menu-price">Rp18.000</p>
            <button class="btn-tambah" data-bs-toggle="modal" data-bs-target="#menuModal">
              <i class="bi bi-plus-lg"></i> Tambah
            </button>
          </div>
        </div>
      </div>

      <div class="col-6 col-md-4 col-lg-3">
        <div class="menu-card">
          <div class="menu-img">
            <img src="img/Peach_Tea.jpg" alt="Peach Tea">
          </div>
          <div class="menu-body">
            <h6 class="menu-title">Peach Tea</h6>
            <p class="menu-price">Rp18.000</p>
            <button class="btn-tambah" data-bs-toggle="modal" data-bs-target="#menuModal">
              <i class="bi bi-plus-lg"></i> Tambah
            </button>
          </div>
        </div>
      </div>

      <div class="col-6 col-md-4 col-lg-3">
        <div class="menu-card">
          <div class="menu-img">
            <img src="img/green_tea.jpg" alt="Green Tea">
          </div>
          <div class="menu-body">
            <h6 class="menu-title">Green Tea</h6>
            <p class="menu-price">Rp18.000</p>
            <button class="btn-tambah" data-bs-toggle="modal" data-bs-target="#menuModal">
              <i class="bi bi-plus-lg"></i> Tambah
            </button>
          </div>
        </div>
      </div>

      <div class="col-6 col-md-4 col-lg-3">
        <div class="menu-card">
          <div class="menu-img">
            <img src="img/EarlGreyTea.jpg" alt="Earl Grey Tea">
          </div>
          <div class="menu-body">
            <h6 class="menu-title">Earl Grey Tea</h6>
            <p class="menu-price">Rp18.000</p>
            <button class="btn-tambah" data-bs-toggle="modal" data-bs-target="#menuModal">
              <i class="bi bi-plus-lg"></i> Tambah
            </button>
          </div>
        </div>
      </div>

      <div class="col-6 col-md-4 col-lg-3">
        <div class="menu-card">
          <div class="menu-img">
            <img src="https://placehold.co/400x300/3e2723/fff?text=Chamomile+Tea" alt="Chamomile Tea">
          </div>
          <div class="menu-body">
            <h6 class="menu-title">Chamomile Tea</h6>
            <p class="menu-price">Rp18.000</p>
            <button class="btn-tambah" data-bs-toggle="modal" data-bs-target="#menuModal">
              <i class="bi bi-plus-lg"></i> Tambah
            </button>
          </div>
        </div>
      </div>
  
      </div>
      <!-- End Menu Cards Grid -->
    </div>

    <!-- Modal Detail Menu -->
    <div class="modal fade" id="menuModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0">
          
          <!-- Modal Body (tanpa header terpisah) -->
          <div class="modal-body p-0">
            
            <!-- Gambar Full Width dengan Tombol X Overlay -->
            <div class="modal-image-wrapper position-relative">
              <img src="img/espresso.webp" class="img-fluid w-100" alt="Menu">
              
              <!-- Tombol X di Pojok Kanan Atas -->
              <button type="button" class="btn-close-modal" data-bs-dismiss="modal">
                <i class="bi bi-x-lg"></i>
              </button>
            </div>

            <!-- Konten Modal -->
            <div class="p-4">
              
              <!-- Info -->
              <h5 class="fw-bold mb-1">Espresso</h5>
              <p class="text-primary fw-bold mb-1">Rp18.000</p>
              <p class="text-muted small mb-3">Kopi espresso murni dengan rasa yang kuat</p>

              <!-- Ukuran -->
              <div class="mb-3">
                <label class="form-label small fw-bold text-uppercase">Ukuran</label>
                <div class="btn-group w-100">
                  <input type="radio" class="btn-check" name="size" id="sizeRegular" checked>
                  <label class="btn btn-outline-secondary" for="sizeRegular">Regular</label>
                  
                  <input type="radio" class="btn-check" name="size" id="sizeLarge">
                  <label class="btn btn-outline-secondary" for="sizeLarge">Large (+Rp5.000)</label>
                </div>
              </div>

              <!-- Es -->
              <div class="mb-3">
                <label class="form-label small fw-bold text-uppercase">Pilihan Es</label>
                <select class="form-select">
                  <option>Es Normal</option>
                  <option>Es Sedikit</option>
                  <option>Tanpa Es</option>
                  <option>Es Banyak</option>
                </select>
              </div>

              <!-- Catatan -->
              <div class="mb-3">
                <label class="form-label small fw-bold text-uppercase">Catatan</label>
                <textarea class="form-control" rows="2" placeholder="Opsional..."></textarea>
              </div>

              <hr>

              <!-- Quantity -->
              <div class="d-flex justify-content-between align-items-center mb-4">
                <label class="fw-bold mb-0">Total Order</label>
                <div class="d-flex align-items-center gap-3">
                  <button class="btn btn-outline-secondary btn-sm rounded-circle" style="width: 32px; height: 32px; padding: 0;" type="button" id="btnMinus">
                    <i class="bi bi-dash"></i>
                  </button>
                  <span class="fw-bold" id="qtyValue">1</span>
                  <button class="btn btn-outline-secondary btn-sm rounded-circle" style="width: 32px; height: 32px; padding: 0;" type="button" id="btnPlus">
                    <i class="bi bi-plus"></i>
                  </button>
                </div>
              </div>

              <!-- Tombol -->
              <div class="d-grid gap-2">
                <button class="btn btn-dark btn-lg title-button" type="button">Tambah ke Keranjang</button>
              </div>

            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- end menu modal -->
  </div>
  <!-- End Menu Section -->

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="{{ asset('customer/assets/js/script.js') }}"></script>
</body>
</html>