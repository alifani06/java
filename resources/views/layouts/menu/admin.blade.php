<li class="nav-header">
    Dashboard</li>
<li class="nav-item">
    <a href="{{ url('admin') }}" class="nav-link {{ request()->is('admin') ? 'active' : '' }}">
        <i class="nav-icon fas fa-home"></i>
        <p>
            Dashboard
        </p>
    </a>
</li>
<li class="nav-header">Search</li>

<div class="form-inline">
    <div class="input-group" data-widget="sidebar-search">
        <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
        <div class="input-group-append">
            <button class="btn btn-sidebar">
                <i class="fas fa-search fa-fw"></i>
            </button>
        </div>
    </div>
</div>
<li class="nav-header">Menu</li>
<li
    class="nav-item {{ request()->is('admin/karyawan*') ||
    request()->is('admin/user*') ||
    request()->is('admin/akses*') ||
    request()->is('admin/departemen*')||
    request()->is('admin/pelanggan*')||
    // request()->is('admin/barang*')||
    request()->is('admin/produk*')||
    request()->is('admin/toko*')||
    request()->is('admin/harga*')||
    request()->is('admin/klasifikasi*')||
    request()->is('admin/input*')
  
        ? 'menu-open'
        : '' }}">
    <a href="#"
        class="nav-link {{ request()->is('admin/karyawan*') ||
        request()->is('admin/user*') ||
        request()->is('admin/akses*') ||
        request()->is('admin/departemen*')||
        request()->is('admin/pelanggan*')||
        // request()->is('admin/barang*')||
        request()->is('admin/produk*')||
        request()->is('admin/toko*')||
        request()->is('admin/harga*')||
        request()->is('admin/klasifikasi*')||
        request()->is('admin/input*')
      
            ? 'active'
            : '' }}">

        <i class="nav-icon fas fa-grip-horizontal"></i>
        <p>
            <strong style="color: rgb(255, 255, 255);">MASTER</strong>
            <i class="right fas fa-angle-left"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        @if (auth()->check() && auth()->user()->menu['karyawan'])
        <li class="nav-item">
            <a href="{{ url('admin/karyawan') }}"
                class="nav-link {{ request()->is('admin/karyawan*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Data Karyawan</p>
            </a>
        </li>
        @endif

        @if (auth()->check() && auth()->user()->menu['user'])
        <li class="nav-item">
            <a href="{{ url('admin/user') }}" class="nav-link {{ request()->is('admin/user*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Data User</p>
            </a>
        </li>
        @endif

        @if (auth()->check() && auth()->user()->menu['akses'])
        <li class="nav-item">
            <a href="{{ url('admin/akses') }}" class="nav-link {{ request()->is('admin/akses*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Hak Akses</p>
            </a>
        </li>
        @endif

        @if (auth()->check() && auth()->user()->menu['departemen'])
        <li class="nav-item">
            <a href="{{ url('admin/departemen') }}"
                class="nav-link {{ request()->is('admin/departemen*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Data Departemen</p>
            </a>
        </li>
        @endif

        @if (auth()->check() && auth()->user()->menu['pelanggan'])
        <li class="nav-item">
            <a href="{{ url('admin/pelanggan') }}"
                class="nav-link {{ request()->is('admin/pelanggan*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Data Pelanggan</p>
            </a>
        </li>
        @endif

        {{-- @if (auth()->check() && auth()->user()->menu['barang']) --}}
        <li class="nav-item">
            <a href="{{ url('admin/barang') }}"
                class="nav-link {{ request()->is('admin/barang*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Data Barang</p>
            </a>
        </li>
        {{-- @endif --}}
        {{-- @if (auth()->check() && auth()->user()->menu['produk']) --}}
        <li class="nav-item">
            <a href="{{ url('admin/produk') }}"
                class="nav-link {{ request()->is('admin/produk*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Data produk</p>
            </a>
        </li>
        {{-- @endif --}}

        {{-- @if (auth()->check() && auth()->user()->menu['toko']) --}}
        <li class="nav-item">
            <a href="{{ url('admin/toko') }}"
                class="nav-link {{ request()->is('admin/toko*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Data toko</p>
            </a>
        </li>
        {{-- @endif --}}

        {{-- @if (auth()->check() && auth()->user()->menu['harga']) --}}
        <li class="nav-item">
            <a href="{{ url('admin/hargajual') }}"
                class="nav-link {{ request()->is('admin/hargajual*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Data Harga Jual</p>
            </a>
        </li>
        {{-- @endif --}}

        @if (auth()->check() && auth()->user()->menu['klasifikasi'])
        <li class="nav-item">
            <a href="{{ url('admin/klasifikasi') }}"
                class="nav-link {{ request()->is('admin/klasifikasi*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Data Klasifikasi</p>
            </a>
        </li>
        @endif

        @if (auth()->check() && auth()->user()->menu['input'])
        <li class="nav-item">
            <a href="{{ url('admin/input ') }}"
                class="nav-link {{ request()->is('admin/input *') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">input stok barang jadi</p>
            </a>
        </li>
        @endif

    </ul>
</li>

<li
    class="nav-item {{ request()->is('admin/pemesanan_produk*') 
    // request()->is('admin/user*') ||
    // request()->is('admin/input*')
  
        ? 'menu-open'
        : '' }}">
    <a href="#"
        class="nav-link {{ request()->is('admin/pemesanan_produk*') 
        // request()->is('admin/user*') ||
        // request()->is('admin/input*')
      
            ? 'active'
            : '' }}">

        <i class="nav-icon fas fa-grip-horizontal"></i>
        <p>
            <strong style="color: rgb(255, 255, 255);">Transaksi</strong>
            <i class="right fas fa-angle-left"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
        <li class="nav-item">
            <a href="{{ url('admin/pemesanan_produk') }}"
                class="nav-link {{ request()->is('admin/pemesanan_produk*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Pemesanan Produk</p>
            </a>
        </li>
        {{-- @endif --}}
    </ul>
</li>

<li
    class="nav-item {{ request()->is('admin/inquery_pemesananproduk*') 
    // request()->is('admin/user*') ||
    // request()->is('admin/input*')
  
        ? 'menu-open'
        : '' }}">
    <a href="#"
        class="nav-link {{ request()->is('admin/inquery_pemesananproduk*') 
        // request()->is('admin/user*') ||
        // request()->is('admin/input*')
      
            ? 'active'
            : '' }}">

        <i class="nav-icon fas fa-grip-horizontal"></i>
        <p>
            <strong style="color: rgb(255, 255, 255);">Finance</strong>
            <i class="right fas fa-angle-left"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
        <li class="nav-item">
            <a href="{{ url('admin/inquery_pemesananproduk') }}"
                class="nav-link {{ request()->is('admin/inquery_pemesananproduk*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Inquery Pemesanan Produk</p>
            </a>
        </li>
        {{-- @endif --}}
    </ul>
</li>

<li
    class="nav-item {{ request()->is('admin/laporan_pemesananproduk*') 
    // request()->is('admin/user*') ||
    // request()->is('admin/input*')
  
        ? 'menu-open'
        : '' }}">
    <a href="#"
        class="nav-link {{ request()->is('admin/laporan_pemesananproduk*') 
        // request()->is('admin/user*') ||
        // request()->is('admin/input*')
      
            ? 'active'
            : '' }}">

        <i class="nav-icon fas fa-grip-horizontal"></i>
        <p>
            <strong style="color: rgb(255, 255, 255);">Laporan</strong>
            <i class="right fas fa-angle-left"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
        <li class="nav-item">
            <a href="{{ url('admin/laporan_pemesananproduk') }}"
                class="nav-link {{ request()->is('admin/laporan_pemesananproduk*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Laporan Pemesanan Produk</p>
            </a>
        </li>
        {{-- @endif --}}
    </ul>
</li>




<li class="nav-header">Profile</li>
<li class="nav-item">
    <a href="{{ url('admin/profile') }}" class="nav-link {{ request()->is('admin/profile') ? 'active' : '' }}">
        <i class="nav-icon fas fa-user-edit"></i>
        <p>Update Profile</p>
    </a>
<li class="nav-item">
    <a href="#" data-toggle="modal" data-target="#modalLogout" class="nav-link">
        <i class="nav-icon fas fa-sign-out-alt"></i>
        <p>Logout</p>
    </a>
</li>
