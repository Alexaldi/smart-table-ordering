<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Cari Menu</title>

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet" />
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet" />

  <link rel="stylesheet" href="{{ asset('customer/assets/css/search.css') }}" />
</head>
<body>

  <!-- Search Header -->
  <header class="search-header sticky-top">
    <div class="container">
      <div class="d-flex align-items-center gap-3">
        
        <!-- Back Button -->
        <a href="{{ route('customer-menu.index') }}" class="back-btn">
          <i class="bi bi-arrow-left"></i>
        </a>

        <!-- Search Input -->
        <div class="search-box flex-grow-1">
          <i class="bi bi-search search-icon"></i>
          <input 
            type="text" 
            class="form-control search-input" 
            placeholder="What are you craving today?"
          />
        </div>

      </div>
    </div>
  </header>

  <!-- Menu List Container -->
  <div class="menu-list-container">
    <div class="container">
      
      <!-- Section Title -->
      <div class="section-header">
        <h5 class="section-title">MENU DISKON</h5>
      </div>

      <!-- Menu Items -->
      <div class="menu-list">
        
        <!-- Menu Item 1 -->
        <div class="menu-list-item">
          <div class="menu-thumb">
            <img src="img/espresso.webp" alt="Espresso">
          </div>
          <div class="menu-info">
            <h6 class="menu-name">
              Espresso 
              <span class="discount-badge">-20%</span>
            </h6>
            <p class="menu-desc">Kopi espresso murni dengan rasa yang kuat dan bold, perfect untuk memulai harimu.</p>
            <div class="menu-price-row">
              <div class="price-wrapper">
                <span class="original-price">Rp22.500</span>
                <span class="discounted-price">Rp18.000</span>
              </div>
              <button class="btn-add">Add</button>
            </div>
          </div>
        </div>

        <!-- Menu Item 2 -->
        <div class="menu-list-item">
          <div class="menu-thumb">
            <img src="img/capucino.jpg" alt="Cappuccino">
          </div>
          <div class="menu-info">
            <h6 class="menu-name">
              Cappuccino 
              <span class="discount-badge">-15%</span>
            </h6>
            <p class="menu-desc">Espresso dengan susu steamed dan foam yang creamy, taburan coklat di atasnya.</p>
            <div class="menu-price-row">
              <div class="price-wrapper">
                <span class="original-price">Rp33.000</span>
                <span class="discounted-price">Rp28.000</span>
              </div>
              <button class="btn-add">Add</button>
            </div>
          </div>
        </div>

        <!-- Menu Item 3 -->
        <div class="menu-list-item">
          <div class="menu-thumb">
            <img src="img/caffee_latte.jpg" alt="Cafe Latte">
          </div>
          <div class="menu-info">
            <h6 class="menu-name">
              Cafe Latte 
              <span class="discount-badge">-10%</span>
            </h6>
            <p class="menu-desc">Perpaduan sempurna espresso dan susu steamed dengan latte art yang indah.</p>
            <div class="menu-price-row">
              <div class="price-wrapper">
                <span class="original-price">Rp33.000</span>
                <span class="discounted-price">Rp30.000</span>
              </div>
              <button class="btn-add">Add</button>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>