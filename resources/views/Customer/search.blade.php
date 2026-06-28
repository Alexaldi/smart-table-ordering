<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cari Menu</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #fff;
            margin: 0;
        }

        .search-header {
            background: #fff;
            border-bottom: 1px solid #e8e8e8;
            padding: 12px 0;
            z-index: 1000;
        }

        .back-btn {
            flex-shrink: 0;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f5f5f5;
            border-radius: 50%;
            color: #222;
            text-decoration: none;
            transition: all 0.2s;
        }

        .back-btn:hover {
            background: #e8e8e8;
            color: #000;
        }

        .back-btn i {
            font-size: 18px;
        }

        .search-box {
            position: relative;
        }

        .suggestion-panel {
            position: absolute;
            top: calc(100% + 8px);
            left: 0;
            right: 0;
            z-index: 1050;
            background: #fff;
            border: 1px solid #eee0d0;
            border-radius: 14px;
            box-shadow: 0 16px 34px rgba(44, 30, 20, 0.12);
            padding: 8px;
            display: none;
            max-height: 310px;
            overflow-y: auto;
        }

        .suggestion-panel.is-visible {
            display: block;
        }

        .suggestion-item {
            width: 100%;
            border: none;
            background: transparent;
            border-radius: 10px;
            padding: 10px 11px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            text-align: left;
            color: #222;
            transition: background 0.18s;
        }

        .suggestion-item:hover {
            background: #fff8ef;
        }

        .suggestion-name {
            display: block;
            font-size: 13px;
            font-weight: 700;
            line-height: 1.25;
        }

        .suggestion-category {
            display: block;
            color: #987252;
            font-size: 11px;
            font-weight: 600;
            margin-top: 2px;
        }

        .suggestion-price {
            color: #d4a574;
            font-size: 12px;
            font-weight: 700;
            flex-shrink: 0;
        }

        .quick-suggestions {
            display: flex;
            gap: 8px;
            overflow-x: auto;
            padding: 2px 0 14px;
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        .quick-suggestions::-webkit-scrollbar {
            display: none;
        }

        .quick-chip {
            flex-shrink: 0;
            border: 1px solid #eadfd2;
            background: #fff;
            color: #6f4c35;
            border-radius: 999px;
            padding: 8px 12px;
            font-size: 12px;
            font-weight: 700;
            transition: all 0.18s;
        }

        .quick-chip:hover {
            background: #d4a574;
            border-color: #d4a574;
            color: #fff;
        }

        .search-submit {
            position: absolute;
            left: 7px;
            top: 50%;
            transform: translateY(-50%);
            width: 34px;
            height: 34px;
            border: none;
            border-radius: 50%;
            background: transparent;
            color: #999;
            font-size: 17px;
            z-index: 1;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.18s;
        }

        .search-submit:hover {
            background: #f1ebe4;
            color: #6f4c35;
        }

        .search-input {
            padding: 12px 16px 12px 44px;
            border: 1px solid #e0e0e0;
            border-radius: 12px;
            font-size: 14px;
            background: #f9f9f9;
            transition: all 0.2s;
        }

        .search-input:focus {
            outline: none;
            border-color: #d4a574;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(212, 165, 116, 0.18);
        }

        .search-input::placeholder {
            color: #999;
        }

        .cart-link {
            flex-shrink: 0;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #222;
            color: #fff;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .badge-cart {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #e74c3c;
            color: #fff;
            border-radius: 50%;
            min-width: 20px;
            height: 20px;
            padding: 0 5px;
            font-size: 11px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid #fff;
            font-weight: 700;
        }

        .menu-list-container {
            background: #fff;
            min-height: calc(100vh - 80px);
            padding-bottom: 40px;
        }

        .section-header {
            padding: 24px 0 16px;
            margin-bottom: 8px;
        }

        .section-title {
            font-size: 18px;
            font-weight: 700;
            color: #222;
            margin-bottom: 4px;
            text-transform: uppercase;
            letter-spacing: 0;
        }

        .section-meta {
            color: #777;
            font-size: 13px;
            margin: 0;
        }

        .menu-list {
            border-top: 1px solid #f0f0f0;
        }

        .menu-list-item {
            display: flex;
            gap: 16px;
            padding: 20px 0;
            border-bottom: 1px solid #f0f0f0;
            align-items: flex-start;
            transition: background 0.2s;
        }

        .menu-list-item.is-hidden {
            display: none;
        }

        .menu-list-item:hover {
            background: #fafafa;
            margin: 0 -15px;
            padding-left: 15px;
            padding-right: 15px;
        }

        .menu-thumb {
            flex-shrink: 0;
            width: 90px;
            height: 90px;
            border-radius: 10px;
            overflow: hidden;
            background: #f5f5f5;
            position: relative;
        }

        .menu-thumb img {
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
            font-size: 1.55rem;
        }

        .placeholder-visual span {
            font-size: 10px;
            font-weight: 600;
            padding: 0 6px;
        }

        .menu-info {
            flex-grow: 1;
            min-width: 0;
        }

        .menu-name {
            font-size: 15px;
            font-weight: 700;
            color: #222;
            margin-bottom: 5px;
            text-transform: uppercase;
            line-height: 1.3;
        }

        .menu-category {
            display: inline-flex;
            align-items: center;
            color: #d4a574;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 6px;
        }

        .menu-desc {
            font-size: 13px;
            color: #666;
            margin-bottom: 10px;
            line-height: 1.5;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .menu-price-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }

        .price-wrapper {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .original-price {
            font-size: 12px;
            color: #999;
            text-decoration: line-through;
        }

        .discounted-price {
            font-size: 15px;
            font-weight: 700;
            color: #222;
        }

        .discount-badge {
            position: absolute;
            top: 6px;
            left: 6px;
            background: #ff4757;
            color: #fff;
            padding: 3px 7px;
            border-radius: 5px;
            font-size: 10px;
            font-weight: 700;
            z-index: 2;
        }

        .btn-add {
            flex-shrink: 0;
            padding: 8px 24px;
            background: #fff;
            border: 1.5px solid #d4a574;
            border-radius: 8px;
            color: #d4a574;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-add:hover {
            background: #d4a574;
            color: #fff;
        }

        .empty-state {
            border-top: 1px solid #f0f0f0;
            padding: 56px 18px;
            text-align: center;
            color: #777;
        }

        .empty-state i {
            display: block;
            font-size: 48px;
            color: #d4a574;
            margin-bottom: 12px;
        }

        .empty-state h5 {
            color: #222;
            font-weight: 700;
        }

        .btn-browse {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 11px 20px;
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

        .customer-toast {
            position: fixed;
            left: 50%;
            bottom: 22px;
            transform: translateX(-50%);
            z-index: 1100;
            width: min(520px, calc(100% - 28px));
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            background: rgba(34, 34, 34, 0.96);
            color: #fff;
            border-radius: 14px;
            padding: 12px 14px 12px 16px;
            box-shadow: 0 18px 40px rgba(0, 0, 0, 0.22);
        }

        .customer-toast .toast-message {
            display: flex;
            align-items: center;
            gap: 10px;
            min-width: 0;
            font-size: 13px;
            font-weight: 600;
        }

        .customer-toast .toast-message i {
            color: #d4a574;
            font-size: 18px;
        }

        .toast-cart-link {
            border: 1px solid rgba(212, 165, 116, 0.8);
            background: #d4a574;
            color: #fff;
            border-radius: 9px;
            padding: 8px 11px;
            text-decoration: none;
            font-size: 12px;
            font-weight: 700;
            flex-shrink: 0;
        }

        .toast-cart-link:hover {
            color: #fff;
            background: #c49464;
        }

        @media (max-width: 576px) {
            .menu-list-container {
                padding-bottom: 20px;
            }

            .section-header {
                padding: 16px 0 12px;
            }

            .section-title {
                font-size: 16px;
            }

            .menu-list-item {
                padding: 16px 0;
                gap: 12px;
            }

            .menu-thumb {
                width: 75px;
                height: 75px;
            }

            .menu-name {
                font-size: 14px;
            }

            .menu-desc {
                font-size: 12px;
                margin-bottom: 8px;
            }

            .discounted-price {
                font-size: 14px;
            }

            .btn-add {
                padding: 6px 16px;
                font-size: 12px;
            }
        }

        @media (min-width: 768px) {
            .menu-thumb {
                width: 100px;
                height: 100px;
            }

            .menu-name {
                font-size: 16px;
            }

            .menu-desc {
                font-size: 14px;
            }

            .btn-add {
                padding: 8px 28px;
            }
        }
    </style>
</head>

<body>
    @if (session('success'))
        <div class="customer-toast" id="customerToast" role="status">
            <div class="toast-message">
                <i class="bi bi-check-circle-fill"></i>
                <span>{{ session('success') }}</span>
            </div>
            <a href="{{ route('customer-menu.cart', ['token' => $token]) }}" class="toast-cart-link">Lihat Keranjang</a>
        </div>
    @endif

    <header class="search-header sticky-top">
        <div class="container">
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('customer-menu.index', ['token' => $token]) }}" class="back-btn" aria-label="Kembali ke menu">
                    <i class="bi bi-arrow-left"></i>
                </a>

                <form action="{{ route('customer-menu.search', ['token' => $token]) }}" method="GET" class="search-box flex-grow-1">
                    <button type="submit" class="search-submit" aria-label="Cari">
                        <i class="bi bi-search"></i>
                    </button>
                    <input type="text" name="q" value="{{ $keyword }}" class="form-control search-input" id="liveSearchInput" placeholder="What are you craving today?" autocomplete="off" autofocus>
                    <div class="suggestion-panel" id="suggestionPanel"></div>
                </form>

                <a href="{{ route('customer-menu.cart', ['token' => $token]) }}" class="cart-link position-relative" aria-label="Keranjang">
                    <i class="bi bi-bag"></i>
                    @if ($cartCount > 0)
                        <span class="badge-cart">{{ $cartCount }}</span>
                    @endif
                </a>
            </div>
        </div>
    </header>

    <main class="menu-list-container">
        <div class="container">
            <div class="section-header">
                <h5 class="section-title">
                    @if ($keyword)
                        Hasil Pencarian
                    @else
                        Semua Menu
                    @endif
                </h5>
                <p class="section-meta">
                    @if ($keyword)
                        <span id="searchMeta">"{{ $keyword }}" - {{ $menuItems->count() }} menu ditemukan</span>
                    @else
                        <span id="searchMeta">{{ $menuItems->count() }} menu tersedia</span>
                    @endif
                </p>
            </div>

            @if ($menuItems->count() > 0)
                <div class="quick-suggestions" id="quickSuggestions">
                    @foreach ($menuItems->take(10) as $quickItem)
                        <button class="quick-chip" type="button" data-suggestion-value="{{ $quickItem->name }}">{{ $quickItem->name }}</button>
                    @endforeach
                </div>

                <div class="menu-list" id="menuList">
                    @foreach ($menuItems as $menuItem)
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
                            $finalPrice = $menuItem->finalPrice();
                        @endphp

                        <article
                            class="menu-list-item"
                            data-menu-item
                            data-name="{{ \Illuminate\Support\Str::lower($menuItem->name) }}"
                            data-category="{{ \Illuminate\Support\Str::lower($menuItem->category->name ?? 'menu') }}"
                            data-description="{{ \Illuminate\Support\Str::lower($menuItem->description ?? '') }}"
                            data-display-name="{{ $menuItem->name }}"
                            data-display-category="{{ $menuItem->category->name ?? 'Menu' }}"
                            data-display-price="Rp{{ number_format($hasDiscount ? $finalPrice : $menuItem->price, 0, ',', '.') }}"
                        >
                            <div class="menu-thumb">
                                @if ($hasDiscount)
                                    <span class="discount-badge">{{ rtrim(rtrim(number_format($discountPercentage, 2, ',', '.'), '0'), ',') }}%</span>
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

                            <div class="menu-info">
                                <h6 class="menu-name">{{ $menuItem->name }}</h6>
                                <div class="menu-category">{{ $menuItem->category->name ?? 'Menu' }}</div>
                                <p class="menu-desc">{{ $menuItem->description ?: 'Menu favorit coffee shop kami.' }}</p>
                                <div class="menu-price-row">
                                    <div class="price-wrapper">
                                        @if ($hasDiscount)
                                            <span class="original-price">Rp{{ number_format($menuItem->price, 0, ',', '.') }}</span>
                                            <span class="discounted-price">Rp{{ number_format($finalPrice, 0, ',', '.') }}</span>
                                        @else
                                            <span class="discounted-price">Rp{{ number_format($menuItem->price, 0, ',', '.') }}</span>
                                        @endif
                                    </div>

                                    <form action="{{ route('customer-menu.cart.add', ['token' => $token]) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="menu_item_id" value="{{ $menuItem->id }}">
                                        <input type="hidden" name="quantity" value="1">
                                        <input type="hidden" name="notes" value="">
                                        <button type="submit" class="btn-add">Add</button>
                                    </form>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            @else
                <div class="empty-state" id="emptyState">
                    <i class="bi bi-search"></i>
                    <h5 class="mb-2">Menu tidak ditemukan</h5>
                    <p class="mb-4">Coba kata kunci lain atau lihat semua menu dari meja {{ $table->table_number }}.</p>
                    <a href="{{ route('customer-menu.index', ['token' => $token]) }}" class="btn-browse">
                        <i class="bi bi-grid"></i> Lihat Menu
                    </a>
                </div>
            @endif

            @if ($menuItems->count() > 0)
                <div class="empty-state d-none" id="liveEmptyState">
                    <i class="bi bi-search"></i>
                    <h5 class="mb-2">Menu tidak ditemukan</h5>
                    <p class="mb-4">Coba kata kunci lain atau pilih suggestion yang tersedia.</p>
                    <button type="button" class="btn-browse border-0" id="clearSearchButton">
                        <i class="bi bi-x-circle"></i> Bersihkan Pencarian
                    </button>
                </div>
            @endif
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const customerToast = document.getElementById('customerToast');
        if (customerToast) {
            window.setTimeout(() => {
                customerToast.remove();
            }, 5200);
        }

        const liveSearchInput = document.getElementById('liveSearchInput');
        const suggestionPanel = document.getElementById('suggestionPanel');
        const searchMeta = document.getElementById('searchMeta');
        const menuItems = Array.from(document.querySelectorAll('[data-menu-item]'));
        const liveEmptyState = document.getElementById('liveEmptyState');
        const menuList = document.getElementById('menuList');
        const quickSuggestions = document.getElementById('quickSuggestions');
        const clearSearchButton = document.getElementById('clearSearchButton');
        const totalMenus = menuItems.length;

        function normalize(value) {
            return String(value || '').toLowerCase().trim();
        }

        function searchableText(item) {
            return [
                item.dataset.name,
                item.dataset.category,
                item.dataset.description
            ].join(' ');
        }

        function setMeta(query, count) {
            if (!searchMeta) {
                return;
            }

            searchMeta.textContent = query
                ? `"${query}" - ${count} menu ditemukan`
                : `${totalMenus} menu tersedia`;
        }

        function renderSuggestions(query, matches) {
            if (!suggestionPanel) {
                return;
            }

            if (!query || matches.length === 0) {
                suggestionPanel.classList.remove('is-visible');
                suggestionPanel.innerHTML = '';
                return;
            }

            suggestionPanel.innerHTML = matches.slice(0, 7).map((item) => `
                <button class="suggestion-item" type="button" data-suggestion-value="${item.dataset.displayName}">
                    <span>
                        <span class="suggestion-name">${item.dataset.displayName}</span>
                        <span class="suggestion-category">${item.dataset.displayCategory}</span>
                    </span>
                    <span class="suggestion-price">${item.dataset.displayPrice}</span>
                </button>
            `).join('');

            suggestionPanel.classList.add('is-visible');
        }

        function applySearch(value) {
            const query = normalize(value);
            const matches = [];

            menuItems.forEach((item) => {
                const isMatch = !query || searchableText(item).includes(query);
                item.classList.toggle('is-hidden', !isMatch);

                if (isMatch) {
                    matches.push(item);
                }
            });

            setMeta(query, matches.length);
            renderSuggestions(query, matches);

            if (liveEmptyState && menuList) {
                liveEmptyState.classList.toggle('d-none', matches.length > 0);
                menuList.classList.toggle('d-none', matches.length === 0);
            }

            if (quickSuggestions) {
                quickSuggestions.classList.toggle('d-none', Boolean(query));
            }
        }

        if (liveSearchInput) {
            liveSearchInput.closest('form')?.addEventListener('submit', (event) => {
                event.preventDefault();
                applySearch(liveSearchInput.value);
                suggestionPanel?.classList.remove('is-visible');
                liveSearchInput.blur();
            });

            liveSearchInput.addEventListener('input', () => {
                applySearch(liveSearchInput.value);
            });

            liveSearchInput.addEventListener('focus', () => {
                applySearch(liveSearchInput.value);
            });

            applySearch(liveSearchInput.value);
        }

        document.addEventListener('click', (event) => {
            const suggestionButton = event.target.closest('[data-suggestion-value]');

            if (suggestionButton && liveSearchInput) {
                liveSearchInput.value = suggestionButton.dataset.suggestionValue;
                applySearch(liveSearchInput.value);
                suggestionPanel?.classList.remove('is-visible');
                return;
            }

            if (!event.target.closest('.search-box')) {
                suggestionPanel?.classList.remove('is-visible');
            }
        });

        if (clearSearchButton && liveSearchInput) {
            clearSearchButton.addEventListener('click', () => {
                liveSearchInput.value = '';
                applySearch('');
                liveSearchInput.focus();
            });
        }
    </script>
</body>

</html>
