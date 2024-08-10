<li class="nav-header">
    Dashboard</li>
<li class="nav-item">
    <a href="{{ url('toko_slawi') }}" class="nav-link {{ request()->is('toko_slawi') ? 'active' : '' }}">
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
    class="nav-item {{ request()->is('toko_slawi/karyawan*') ||
    request()->is('toko_slawi/user*') ||
    request()->is('toko_slawi/akses*') ||
    request()->is('toko_slawi/departemen*')||
    request()->is('toko_slawi/pelanggan*')||
    // request()->is('toko_slawi/barang*')||
    request()->is('toko_slawi/produk*')||
    request()->is('toko_slawi/toko*')||
    // request()->is('toko_slawi/harga*')||
    request()->is('toko_slawi/klasifikasi*')||
    request()->is('toko_slawi/metode_pembayaran*')||
    request()->is('toko_slawi/input*')||
    request()->is('toko_slawi/data_stokbarangjadi*')
  
        ? 'menu-open'
        : '' }}">
    <a href="#"
        class="nav-link {{ request()->is('toko_slawi/karyawan*') ||
        request()->is('toko_slawi/user*') ||
        request()->is('toko_slawi/akses*') ||
        request()->is('toko_slawi/departemen*')||
        request()->is('toko_slawi/pelanggan*')||
        // request()->is('toko_slawi/barang*')||
        request()->is('toko_slawi/produk*')||
        request()->is('toko_slawi/toko*')||
        // request()->is('toko_slawi/harga*')||
        request()->is('toko_slawi/klasifikasi*')||
        request()->is('toko_slawi/metode_pembayaran*')||
        request()->is('toko_slawi/input*')||
        request()->is('toko_slawi/data_stokbarangjadi*')
      
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
            <a href="{{ url('toko_slawi/karyawan') }}"
                class="nav-link {{ request()->is('toko_slawi/karyawan*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Data Karyawan</p>
            </a>
        </li>
        @endif

        @if (auth()->check() && auth()->user()->menu['user'])
        <li class="nav-item">
            <a href="{{ url('toko_slawi/user') }}" class="nav-link {{ request()->is('toko_slawi/user*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Data User</p>
            </a>
        </li>
        @endif

        @if (auth()->check() && auth()->user()->menu['akses'])
        <li class="nav-item">
            <a href="{{ url('toko_slawi/akses') }}" class="nav-link {{ request()->is('toko_slawi/akses*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Hak Akses</p>
            </a>
        </li>
        @endif

        @if (auth()->check() && auth()->user()->menu['departemen'])
        <li class="nav-item">
            <a href="{{ url('toko_slawi/departemen') }}"
                class="nav-link {{ request()->is('toko_slawi/departemen*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Data Departemen</p>
            </a>
        </li>
        @endif

        @if (auth()->check() && auth()->user()->menu['pelanggan'])
        <li class="nav-item">
            <a href="{{ url('toko_slawi/pelanggan') }}"
                class="nav-link {{ request()->is('toko_slawi/pelanggan*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Data Pelanggan</p>
            </a>
        </li>
        @endif

        {{-- @if (auth()->check() && auth()->user()->menu['barang']) --}}
        {{-- <li class="nav-item">
            <a href="{{ url('toko_slawi/barang') }}"
                class="nav-link {{ request()->is('toko_slawi/barang*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Data Barang</p>
            </a>
        </li> --}}
        {{-- @endif --}}
        {{-- @if (auth()->check() && auth()->user()->menu['produk']) --}}
        <li class="nav-item">
            <a href="{{ url('toko_slawi/produk') }}"
                class="nav-link {{ request()->is('toko_slawi/produk*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Data Produk</p>
            </a>
        </li>
        {{-- @endif --}}

        {{-- @if (auth()->check() && auth()->user()->menu['toko']) --}}
        <li class="nav-item">
            <a href="{{ url('toko_slawi/toko') }}"
                class="nav-link {{ request()->is('toko_slawi/toko*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Data Toko</p>
            </a>
        </li>
        {{-- @endif --}}

        {{-- @if (auth()->check() && auth()->user()->menu['harga']) --}}
        {{-- <li class="nav-item">
            <a href="{{ url('toko_slawi/hargajual') }}"
                class="nav-link {{ request()->is('toko_slawi/hargajual*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Data Harga Jual</p>
            </a>
        </li> --}}
        {{-- @endif --}}

        @if (auth()->check() && auth()->user()->menu['klasifikasi'])
        <li class="nav-item">
            <a href="{{ url('toko_slawi/klasifikasi') }}"
                class="nav-link {{ request()->is('toko_slawi/klasifikasi*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Data Klasifikasi</p>
            </a>
        </li>
        @endif
        {{-- @if (auth()->check() && auth()->user()->menu['klasifikasi']) --}}
        <li class="nav-item">
            <a href="{{ url('toko_slawi/metode_pembayaran') }}"
                class="nav-link {{ request()->is('toko_slawi/metode_pembayaran*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Data Jenis Pembayaran</p>
            </a>
        </li>
        {{-- @endif --}}

        {{-- @if (auth()->check() && auth()->user()->menu['klasifikasi']) --}}
        <li class="nav-item">
            <a href="{{ url('toko_slawi/data_stokbarangjadi') }}"
                class="nav-link {{ request()->is('toko_slawi/data_stokbarangjadi*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Data Stok Barang Jadi</p>
            </a>
        </li>
        {{-- @endif --}}

        {{-- @if (auth()->check() && auth()->user()->menu['input'])
        <li class="nav-item">
            <a href="{{ url('toko_slawi/input ') }}"
                class="nav-link {{ request()->is('toko_slawi/input *') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">input stok barang jadi</p>
            </a>
        </li>
        @endif --}}

    </ul>
</li>

<li
    class="nav-item {{ request()->is('toko_slawi/pemesanan_produk*') ||
    request()->is('toko_slawi/penjualan_produk*') ||
    request()->is('toko_slawi/hargajual*') || 
    request()->is('toko_slawi/stok_barangjadi*')||  
    request()->is('toko_slawi/permintaan_produk*')|| 
    request()->is('toko_slawi/pengiriman_barangjadi*')|| 
    request()->is('toko_slawireturn_barangjadi*') 
    // request()->is('toko_slawi/input*')
  
        ? 'menu-open'
        : '' }}">
    <a href="#"
        class="nav-link {{ request()->is('toko_slawi/pemesanan_produk*') ||
        request()->is('toko_slawi/penjualan_produk*') ||
        request()->is('toko_slawi/hargajual*') || 
        request()->is('toko_slawi/stok_barangjadi*')||  
        request()->is('toko_slawi/permintaan_produk*')||  
        request()->is('toko_slawi/pengiriman_barangjadi*')||  
        request()->is('toko_slawi/retur_barangjadi*')  
        // request()->is('toko_slawi/input*')
      
            ? 'active'
            : '' }}">

        <i class="nav-icon fas fa-grip-horizontal"></i>
        <p>
            <strong style="color: rgb(255, 255, 255);">TRANSAKSI</strong>
            <i class="right fas fa-angle-left"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
        <li class="nav-item">
            <a href="{{ url('toko_slawi/pemesanan_produk') }}"
                class="nav-link {{ request()->is('toko_slawi/pemesanan_produk*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Pemesanan Produk</p>
            </a>
        </li>
        {{-- @endif --}}

        {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
        <li class="nav-item">
            <a href="{{ url('toko_slawi/penjualan_produk') }}"
                class="nav-link {{ request()->is('toko_slawi/penjualan_produk*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Penjualan Produk</p>
            </a>
        </li>
        {{-- @endif --}}
        {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
        <li class="nav-item">
            <a href="{{ url('toko_slawi/hargajual') }}"
                class="nav-link {{ request()->is('toko_slawi/hargajual*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Perubahan Harga Jual</p>
            </a>
        </li>
        {{-- @endif --}}

          {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
          <li class="nav-item">
            <a href="{{ url('toko_slawi/permintaan_produk') }}"
                class="nav-link {{ request()->is('toko_slawi/permintaan_produk*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Permintaan Produk</p>
            </a>
        </li>
        {{-- @endif --}}
          {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
          <li class="nav-item">
            <a href="{{ url('toko_slawi/stok_barangjadi') }}"
                class="nav-link {{ request()->is('toko_slawi/stok_barangjadi*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Stok Barang Jadi</p>
            </a>
        </li>
        {{-- @endif --}}
    
          {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
          <li class="nav-item">
            <a href="{{ url('toko_slawi/pengiriman_barangjadi') }}"
                class="nav-link {{ request()->is('toko_slawi/pengiriman_barangjadi*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Pengiriman Barang Jadi</p>
            </a>
        </li>
        {{-- @endif --}}

        {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
        <li class="nav-item">
            <a href="{{ url('toko_slawi/retur_barangjadi') }}"
                class="nav-link {{ request()->is('toko_slawi/retur_barangjadi*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Retur Barang Jadi</p>
            </a>
        </li>
        {{-- @endif --}}
    </ul>
</li>

<li
    class="nav-item {{ request()->is('toko_slawi/inquery_pemesananproduk*') ||
    request()->is('toko_slawi/inquery_penjualanproduk*')|| 
    request()->is('toko_slawi/inquery_perubahanharga*')|| 
    request()->is('toko_slawi/inquery_permintaanproduk*')|| 
    request()->is('toko_slawi/inquery_stokbarangjadi*')|| 
    request()->is('toko_slawi/inquery_pengirimanbarangjadi*') 
    // request()->is('toko_slawi/input*')
  
        ? 'menu-open'
        : '' }}">
    <a href="#"
        class="nav-link {{ request()->is('toko_slawi/inquery_pemesananproduk*') ||
        request()->is('toko_slawi/inquery_penjualanproduk*')||
        request()->is('toko_slawi/inquery_perubahanharga*')||
        request()->is('toko_slawi/inquery_permintaanproduk*')||
        request()->is('toko_slawi/inquery_stokbarangjadi*')||
        request()->is('toko_slawi/inquerypengirimankbarangjadi*')
        // request()->is('toko_slawi/input*')
      
            ? 'active'
            : '' }}">

        <i class="nav-icon fas fa-grip-horizontal"></i>
        <p>
            <strong style="color: rgb(255, 255, 255);">FINANCE</strong>
            <i class="right fas fa-angle-left"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
        <li class="nav-item">
            <a href="{{ url('toko_slawi/inquery_pemesananproduk') }}"
                class="nav-link {{ request()->is('toko_slawi/inquery_pemesananproduk*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Inquery Pemesanan Produk</p>
            </a>
        </li>
        {{-- @endif --}}

        {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
        <li class="nav-item">
            <a href="{{ url('toko_slawi/inquery_penjualanproduk') }}"
                class="nav-link {{ request()->is('toko_slawi/inquery_penjualanproduk*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Inquery Penjualan Produk</p>
            </a>
        </li>
        {{-- @endif --}}

        {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
        <li class="nav-item">
            <a href="{{ url('toko_slawi/inquery_perubahanharga') }}"
                class="nav-link {{ request()->is('toko_slawi/inquery_perubahanharga*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Inquery Perubahan Harga</p>
            </a>
        </li>
        {{-- @endif --}}
        {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
        <li class="nav-item">
            <a href="{{ url('toko_slawi/inquery_permintaanproduk') }}"
                class="nav-link {{ request()->is('toko_slawi/inquery_permintaanproduk*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Inquery Permintaan Produk</p>
            </a>
        </li>
        {{-- @endif --}}

        {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
        <li class="nav-item">
            <a href="{{ url('toko_slawi/inquery_stokbarangjadi') }}"
                class="nav-link {{ request()->is('toko_slawi/inquery_stokbarangjadi*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Inquery Stok Barang Jadi</p>
            </a>
        </li>
        {{-- @endif --}}

          {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
          <li class="nav-item">
            <a href="{{ url('toko_slawi/inquery_pengirimanbarangjadi') }}"
                class="nav-link {{ request()->is('toko_slawi/inquery_pengirimanbarangjadi*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Inquery Pengiriman Barang</p>
            </a>
        </li>
        {{-- @endif --}}
    </ul>
</li>

<li
    class="nav-item {{ request()->is('toko_slawi/laporan_pemesananproduk*') ||
    request()->is('toko_slawi/laporan_penjualanproduk*')  ||
    request()->is('toko_slawi/laporan_perubahanharga*')||
    request()->is('toko_slawi/laporan_permintaanproduk*')||
    request()->is('toko_slawi/laporan_stokbarangjadi*')||
    request()->is('toko_slawi/laporan_pengirimanbarangjadi*')
    // request()->is('toko_slawi/input*')
  
        ? 'menu-open'
        : '' }}">
    <a href="#"
        class="nav-link {{ request()->is('toko_slawi/laporan_pemesananproduk*') ||
        request()->is('toko_slawi/laporan_penjualanproduk*')|| 
        request()->is('toko_slawi/laporan_perubahanharga*') ||
        request()->is('toko_slawi/laporan_permintaanproduk*')|| 
        request()->is('toko_slawi/laporan_stokbarangjadi*')|| 
        request()->is('toko_slawi/laporan_spengirimanarangjadi*') 
        // request()->is('toko_slawi/input*')
      
            ? 'active'
            : '' }}">

        <i class="nav-icon fas fa-grip-horizontal"></i>
        <p>
            <strong style="color: rgb(255, 255, 255);">LAPORAN</strong>
            <i class="right fas fa-angle-left"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
        <li class="nav-item">
            <a href="{{ url('toko_slawi/laporan_pemesananproduk') }}"
                class="nav-link {{ request()->is('toko_slawi/laporan_pemesananproduk*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Laporan Pemesanan Produk</p>
            </a>
        </li>
        {{-- @endif --}}

        {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
        <li class="nav-item">
            <a href="{{ url('toko_slawi/laporan_penjualanproduk') }}"
                class="nav-link {{ request()->is('toko_slawi/laporan_penjualanproduk*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Laporan Penjualan Produk</p>
            </a>
        </li>
        {{-- @endif --}}

              {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
              <li class="nav-item">
                <a href="{{ url('toko_slawi/laporan_perubahanharga') }}"
                    class="nav-link {{ request()->is('toko_slawi/laporan_perubahanharga*') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                    <p style="font-size: 14px;">Laporan Perubahan Harga</p>
                </a>
            </li>
            {{-- @endif --}}

              {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
              <li class="nav-item">
                <a href="{{ url('toko_slawi/laporan_permintaanproduk') }}"
                    class="nav-link {{ request()->is('toko_slawi/laporan_permintaanproduk*') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                    <p style="font-size: 14px;">Laporan Permintaan Produk</p>
                </a>
            </li>
            {{-- @endif --}}

              {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
              <li class="nav-item">
                <a href="{{ url('toko_slawi/laporan_stokbarangjadi') }}"
                    class="nav-link {{ request()->is('toko_slawi/laporan_stokbarangjadi*') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                    <p style="font-size: 14px;">Laporan Stok Barang Jadi</p>
                </a>
            </li>
            {{-- @endif --}}

              {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
              <li class="nav-item">
                <a href="{{ url('toko_slawi/laporan_pengirimanbarangjadi') }}"
                    class="nav-link {{ request()->is('toko_slawi/laporan_pengirimanbarangjadi*') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                    <p style="font-size: 14px;">Laporan Pengiriman Barang</p>
                </a>
            </li>
            {{-- @endif --}}
    </ul>
</li>

<li
    class="nav-item {{ request()->is('toko_slawi/stok_tokoslawi*') ||
    request()->is('toko_slawi/retur_tokoslawi*')||
    request()->is('toko_slawi/pengiriman_tokoslawi*')
    // request()->is('toko_slawi/input*')
  
        ? 'menu-open'
        : '' }}">
    <a href="#"
        class="nav-link {{ request()->is('toko_slawi/stok_tokoslawi*') ||
        request()->is('toko_slawi/retur_tokoslawi*')|| 
        request()->is('toko_slawi/rpengirimantokoslawi*') 
        // request()->is('toko_slawi/input*')
      
            ? 'active'
            : '' }}">

        <i class="nav-icon fas fa-grip-horizontal"></i>
        <p>
            <strong style="color: rgb(255, 255, 255);">TOKO SLAWI</strong>
            <i class="right fas fa-angle-left"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
        <li class="nav-item">
            <a href="{{ url('toko_slawi/stok_tokoslawi') }}"
                class="nav-link {{ request()->is('toko_slawi/stok_tokoslawi*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Stok Toko Slawi</p>
            </a>
        </li>
        {{-- @endif --}}

        {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
        <li class="nav-item">
            <a href="{{ url('toko_slawi/retur_tokoslawi') }}"
                class="nav-link {{ request()->is('toko_slawi/retur_tokoslawi*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Retur Toko Slawi</p>
            </a>
        </li>
        {{-- @endif --}}

        {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
        <li class="nav-item">
            <a href="{{ url('toko_slawi/pengiriman_tokoslawi') }}"
                class="nav-link {{ request()->is('toko_slawi/pengiriman_tokoslawi*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Pengiriman Toko Slawi</p>
            </a>
        </li>
        {{-- @endif --}}
    </ul>
</li>




<li class="nav-header">Profile</li>
<li class="nav-item">
    <a href="{{ url('toko_slawi/profile') }}" class="nav-link {{ request()->is('toko_slawi/profile') ? 'active' : '' }}">
        <i class="nav-icon fas fa-user-edit"></i>
        <p>Update Profile</p>
    </a>
<li class="nav-item">
    <a href="#" data-toggle="modal" data-target="#modalLogout" class="nav-link">
        <i class="nav-icon fas fa-sign-out-alt"></i>
        <p>Logout</p>
    </a>
</li>
