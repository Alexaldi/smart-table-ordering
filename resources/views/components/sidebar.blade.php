@php
    $isDashboardActive = request()->routeIs('dashboard');
    $isUsersSection = request()->routeIs('users.*') || request()->routeIs('shifts.*');
    $isUsersActive = request()->routeIs('users.*');
    $isShiftsActive = request()->routeIs('shifts.*');
    $isTablesActive = request()->routeIs('tables.*');
    $isCategoriesActive = request()->routeIs('categories.*');
    $isMenuActive = request()->routeIs('menu.*');
@endphp

<div class="app-sidebar__overlay" data-bs-toggle="sidebar"></div>
<aside class="app-sidebar">
    <div class="side-header">
        <a class="header-brand1" href="{{ route('dashboard') }}">
            <img src="{{ asset('assets/images/brand/logo.png') }}" class="header-brand-img desktop-logo" alt="logo">
            <img src="{{ asset('assets/images/brand/logo-1.png') }}" class="header-brand-img toggle-logo" alt="logo">
            <img src="{{ asset('assets/images/brand/logo-2.png') }}" class="header-brand-img light-logo" alt="logo">
            <img src="{{ asset('assets/images/brand/logo-3.png') }}" class="header-brand-img light-logo1" alt="logo">
        </a></div>
    <ul class="side-menu">
        <li><h3>Main</h3></li>
        
        <li class="slide {{ $isDashboardActive ? 'is-expanded' : '' }}">
            <a class="side-menu__item {{ $isDashboardActive ? 'active' : '' }}"  data-bs-toggle="slide" href="{{ route('dashboard') }}"><i class="side-menu__icon fe fe-home"></i><span class="side-menu__label">Dashboard</span></a>
        </li>
        <li><h3>MASTER DATA</h3></li>
        <li class="slide {{ $isUsersSection ? 'is-expanded' : '' }}">
            <a class="side-menu__item {{ $isUsersSection ? 'active' : '' }}" data-bs-toggle="slide" href="#">
                <i class="side-menu__icon fe fe-users"></i>
                <span class="side-menu__label">Users</span>
                <i class="angle fe fe-chevron-right"></i>
            </a>
            <ul class="slide-menu" style="{{ $isUsersSection ? 'display: block;' : '' }}">
                <li>
                    <a href="{{ route('users.index') }}" class="slide-item {{ $isUsersActive ? 'active' : '' }}">User Management</a>
                </li>
                <li>
                    <a href="{{ route('shifts.index') }}" class="slide-item {{ $isShiftsActive ? 'active' : '' }}">Shift Management</a>
                </li>
            </ul>
        </li>
        <li>
            <a class="side-menu__item {{ $isTablesActive ? 'active' : '' }}" href="{{ route('tables.index') }}"><i class="side-menu__icon fe fe-layers"></i><span class="side-menu__label">Tables</span></a>
        </li>
        <li>
            <a class="side-menu__item {{ $isCategoriesActive ? 'active' : '' }}" href="{{ route('categories.index') }}"><i class="side-menu__icon fe fe-grid"></i><span class="side-menu__label">Categories</span></a>
        </li>
        <li>
            <a class="side-menu__item {{ $isMenuActive ? 'active' : '' }}" href="{{ route('menu.index') }}"><i class="side-menu__icon fa fa-coffee"></i><span class="side-menu__label">Menus</span></a>
        </li>
    </ul>
</aside>
