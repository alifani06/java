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
        @if (auth()->check() && auth()->user()->menu['pelanggan'])
        <li class="nav-item">
            <a href="{{ url('toko_slawi/pelanggan') }}"
                class="nav-link {{ request()->is('toko_slawi/pelanggan*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Data Pelanggan</p>
            </a>
        </li>
        @endif

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

          {{-- @if (auth()->check() && auth()->user()->menu['toko']) --}}
          <li class="nav-item">
            <a href="{{ url('toko_slawi/stok_tokoslawi') }}"
                class="nav-link {{ request()->is('toko_slawi/stok_tokoslawi*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Data Stok Produk</p>
            </a>
        </li>
        {{-- @endif --}}    


    </ul>
</li>

<li
    class="nav-item {{ request()->is('toko_slawi/pemesanan_produk*') ||
    request()->is('toko_slawi/penjualan_produk*') ||
    request()->is('toko_slawi/hargajual*') || 
    request()->is('toko_slawi/stok_barangjadi*')||  
    request()->is('toko_slawi/permintaan_produk*')|| 
    request()->is('toko_slawi/pengiriman_barangjadi*')|| 
    request()->is('toko_slawireturn_barangjadi*')|| 
    request()->is('toko_slawi/pemindahan_tokoslawi*') 
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
        request()->is('toko_slawi/retur_barangjadi*')||  
        request()->is('toko_slawi/pemindahan_tokoslawi*')  
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
            <a href="{{ url('toko_slawi/permintaan_produk') }}"
                class="nav-link {{ request()->is('toko_slawi/permintaan_produk*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Permintaan Produk</p>
            </a>
        </li>
        {{-- @endif --}}

         {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
         <li class="nav-item">
            <a href="{{ url('toko_slawi/stok_tokoslawi') }}"
                class="nav-link {{ request()->is('toko_slawi/stok_tokoslawi*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Stok Produk</p>
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
            <a href="{{ url('toko_slawi/pemindahan_tokoslawi') }}"
                class="nav-link {{ request()->is('toko_slawi/pemindahan_tokoslawi*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Pemindahan Produk</p>
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
    request()->is('toko_slawi/pengiriman_tokoslawi*')|| 
    request()->is('toko_slawi/inquery_pemindahanslawi*')|| 
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
        request()->is('toko_slawi/pengiriman_tokoslawi*')||
        request()->is('toko_slawi/inquery_pemindahanslawi*')||
        request()->is('toko_slawi/inquerypengirimanbarangjadi*')
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
            <a href="{{ url('toko_slawi/inquery_permintaanproduk') }}"
                class="nav-link {{ request()->is('toko_slawi/inquery_permintaanproduk*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Inquery Permintaan Produk</p>
            </a>
        </li>
        {{-- @endif --}}

        {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
        <li class="nav-item">
            <a href="{{ url('toko_slawi/pengiriman_tokoslawi') }}"
                class="nav-link {{ request()->is('toko_slawi/pengiriman_tokoslawi*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Inquery Pengiriman Slawi</p>
            </a>
        </li>
        {{-- @endif --}}
        {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
        <li class="nav-item">
            <a href="{{ url('toko_slawi/inquery_returslawi') }}"
                class="nav-link {{ request()->is('toko_slawi/inquery_returslawi*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Inquery Retur Slawi</p>
            </a>
        </li>
        {{-- @endif --}}


          {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
          <li class="nav-item">
            <a href="{{ url('toko_slawi/inquery_pemindahanslawi') }}"
                class="nav-link {{ request()->is('toko_slawi/inquery_pemindahanslawi*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Inquery Pemindahan Produk</p>
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
    request()->is('toko_slawi/laporan_pengirimanproduk*')||
    request()->is('toko_slawi/laporan_returslawi*')||
    request()->is('toko_slawi/laporan_pemindahanslawi*')||
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
        request()->is('toko_slawi/laporan_pengirimanproduk*')|| 
        request()->is('toko_slawi/laporan_returslawi*')|| 
        request()->is('toko_slawi/laporan_pemindahanslawi*')|| 
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
                <a href="{{ url('toko_slawi/laporan_permintaanproduk') }}"
                    class="nav-link {{ request()->is('toko_slawi/laporan_permintaanproduk*') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                    <p style="font-size: 14px;">Laporan Permintaan Produk</p>
                </a>
            </li>
            {{-- @endif --}}

            {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
              <li class="nav-item">
                <a href="{{ url('toko_slawi/laporan_pengirimanproduk') }}"
                    class="nav-link {{ request()->is('toko_slawi/laporan_pengirimanproduk*') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                    <p style="font-size: 14px;">Laporan Pengiriman Produk</p>
                </a>
            </li>
            {{-- @endif --}}

              {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
              <li class="nav-item">
                <a href="{{ url('toko_slawi/laporan_returslawi') }}"
                    class="nav-link {{ request()->is('toko_slawi/laporan_returslawi*') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                    <p style="font-size: 14px;">Laporan Retur Produk</p>
                </a>
            </li>
            {{-- @endif --}}

            {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
              <li class="nav-item">
                <a href="{{ url('toko_slawi/laporan_pemindahanslawi') }}"
                    class="nav-link {{ request()->is('toko_slawi/laporan_pemindahanslawi*') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                    <p style="font-size: 13px;">Laporan Pemindahan Produk</p>
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
