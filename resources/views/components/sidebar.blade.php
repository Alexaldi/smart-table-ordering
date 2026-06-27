<div class="app-sidebar__overlay" data-bs-toggle="sidebar"></div>
<aside class="app-sidebar">
    <div class="side-header">
        <a class="header-brand1" href="{{ route('dashboard') }}">
            <img src="{{ asset('assets/images/brand/logo.png') }}" class="header-brand-img desktop-logo" alt="logo">
            <img src="{{ asset('assets/images/brand/logo-1.png') }}" class="header-brand-img toggle-logo" alt="logo">
            <img src="{{ asset('assets/images/brand/logo-2.png') }}" class="header-brand-img light-logo" alt="logo">
            <img src="{{ asset('assets/images/brand/logo-3.png') }}" class="header-brand-img light-logo1" alt="logo">
        </a><!-- LOGO -->
    </div>
    <ul class="side-menu">
        <li><h3>Main</h3></li>
        <li class="slide">
            <a class="side-menu__item"  data-bs-toggle="slide" href="{{ route('dashboard') }}"><i class="side-menu__icon fe fe-home"></i><span class="side-menu__label">Dashboard</span></a>
        </li>
        <li><h3>MASTER DATA</h3></li>
        <li>
            <a class="side-menu__item" href="{{ route('categories.index') }}"><i class="side-menu__icon fe fe-grid"></i><span class="side-menu__label">Categories</span></a>
        </li>
        <li>
            <a class="side-menu__item" href="{{ route('menu.index') }}"><i class="side-menu__icon fa fa-coffee"></i><span class="side-menu__label">Menu</span></a>
        </li>
    </ul>
</aside>