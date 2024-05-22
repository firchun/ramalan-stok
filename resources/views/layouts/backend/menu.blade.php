<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        {{-- logo aplikasi --}}
        <a href="{{ url('/home') }}" class="app-brand-link">
            <img src="{{ asset('/img/logo.png') }}" style="width: 40px;">
            <span class="app-brand-text demo menu-text fw-bolder ms-2"
                style="text-transform:none;">{{ env('APP_NAME') ?? 'Laravel' }}</span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        <!-- Dashboard -->
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">{{ env('APP_NAME') ?? 'Laravel' }}</span>
        </li>
        <li class="menu-item {{ request()->is('home*') ? 'active' : '' }}">
            <a href="{{ url('/home') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-circle"></i>
                <div data-i18n="Analytics">Dashboard</div>
            </a>
        </li>
        @if (Auth::user()->role == 'Admin')
            <li class="menu-item {{ request()->is('peramalan*') ? 'active' : '' }}">
                <a href="{{ url('/peramalan') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-braille"></i>
                    <div data-i18n="Analytics">Peramalan</div>
                </a>
            </li>
            <li class="menu-item {{ request()->is('pesanan*') ? 'active' : '' }}">
                <a href="{{ url('/pesanan') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-cart"></i>
                    <div data-i18n="Analytics">Pesanan</div>
                </a>
            </li>
        @endif
        {{-- <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Transaksi</span>
        </li>
        <li class="menu-item {{ request()->is('penjualan') ? 'active' : '' }}">
            <a href="{{ url('/penjualan') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-cart"></i>
                <div data-i18n="Analytics">Penjualan Produk</div>
            </a>
        </li> --}}
        @if (Auth::user()->role == 'Admin')
            <li class="menu-header small text-uppercase">
                <span class="menu-header-text">Produk</span>
            </li>
            <li class="menu-item {{ request()->is('jenis_produk') ? 'active' : '' }}">
                <a href="{{ url('/jenis_produk') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-folder"></i>
                    <div data-i18n="Analytics">Jenis Produk</div>
                </a>
            </li>
            <li class="menu-item {{ request()->is('produk') ? 'active' : '' }}">
                <a href="{{ url('/produk') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-folder"></i>
                    <div data-i18n="Analytics">Daftar Produk</div>
                </a>
            </li>
            <li class="menu-header small text-uppercase">
                <span class="menu-header-text">Stok</span>
            </li>
            <li class="menu-item {{ request()->is('stok') ? 'active' : '' }}">
                <a href="{{ url('/stok') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-folder"></i>
                    <div data-i18n="Analytics">Riwayat Stok</div>
                </a>
            </li>
            <li class="menu-item {{ request()->is('stok/mitra') ? 'active' : '' }}">
                <a href="{{ url('/stok/mitra') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-folder"></i>
                    <div data-i18n="Analytics">Stok Mitra</div>
                </a>
            </li>
            <li class="menu-item {{ request()->is('stok/mitra/return') ? 'active' : '' }}">
                <a href="{{ url('/stok/mitra/return') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-folder"></i>
                    <div data-i18n="Analytics">Return Stok</div>
                </a>
            </li>

            <li class="menu-header small text-uppercase">
                <span class="menu-header-text">Pengguna</span>
            </li>
            <li class="menu-item {{ request()->is('users') ? 'active' : '' }}">
                <a href="{{ url('/users') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-user"></i>
                    <div data-i18n="Analytics">Admin</div>
                </a>
            </li>
            <li class="menu-item {{ request()->is('mitra') ? 'active' : '' }}">
                <a href="{{ url('/mitra') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-user"></i>
                    <div data-i18n="Analytics">Mitra</div>
                </a>
            </li>
            <li class="menu-header small text-uppercase">
                <span class="menu-header-text">Laporan</span>
            </li>
            <li class="menu-item {{ request()->is('report/utama') ? 'active' : '' }}">
                <a href="{{ url('/report/utama') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-user"></i>
                    <div data-i18n="Analytics">Laporan Stok Utama</div>
                </a>
            </li>
            <li class="menu-item {{ request()->is('report/mitra') ? 'active' : '' }}">
                <a href="{{ url('/report/mitra') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-user"></i>
                    <div data-i18n="Analytics">Laporan Stok Mitra</div>
                </a>
            </li>
        @elseif(Auth::user()->role == 'Mitra')
            <li class="menu-header small text-uppercase">
                <span class="menu-header-text">Produk</span>
            </li>
            <li class="menu-item {{ request()->is('produk/mitra') ? 'active' : '' }}">
                <a href="{{ url('/produk/mitra') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-folder"></i>
                    <div data-i18n="Analytics">Daftar Produk</div>
                </a>
            </li>
            <li class="menu-header small text-uppercase">
                <span class="menu-header-text">Stok</span>
            </li>
            <li class="menu-item {{ request()->is('stok/mitra/detail*') ? 'active' : '' }}">
                <a href="{{ url('/stok/mitra/detail', Auth::id()) }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-folder"></i>
                    <div data-i18n="Analytics">Data Stok</div>
                </a>
            </li>
            <li class="menu-header small text-uppercase">
                <span class="menu-header-text">Laporan</span>
            </li>
            <li class="menu-item {{ request()->is('report/mitra*') ? 'active' : '' }}">
                <a href="{{ url('/report/mitra', Auth::id()) }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-user"></i>
                    <div data-i18n="Analytics">Laporan Stok</div>
                </a>
            </li>
        @endif
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Akun</span>
        </li>
        <li class="menu-item {{ request()->is('profile') ? 'active' : '' }}">
            <a href="{{ url('/profile') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-user"></i>
                <div data-i18n="Analytics">Profile</div>
            </a>
        </li>

    </ul>
</aside>
