<li class="nav-header">
    Dashboard</li>
<li class="nav-item">
    <a href="{{ url('toko_banjaran') }}" class="nav-link {{ request()->is('toko_banjaran') ? 'active' : '' }}">
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
    class="nav-item {{ request()->is('toko_banjaran/karyawan*') ||
    request()->is('toko_banjaran/pelanggan*')||
    // request()->is('toko_banjaran/barang*')||
    request()->is('toko_banjaran/produk*')||
    request()->is('toko_banjaran/toko*')||
    // request()->is('toko_banjaran/harga*')||
    request()->is('toko_banjaran/klasifikasi*')||
    request()->is('toko_banjaran/metode_pembayaran*')||
    request()->is('toko_banjaran/input*')||
    request()->is('toko_banjaran/data_stokbarangjadi*')
  
        ? 'menu-open'
        : '' }}">
    <a href="#"
        class="nav-link {{ request()->is('toko_banjaran/karyawan*') ||
        request()->is('toko_banjaran/pelanggan*')||
        // request()->is('toko_banjaran/barang*')||
        request()->is('toko_banjaran/produk*')||
        request()->is('toko_banjaran/toko*')||
        // request()->is('toko_banjaran/harga*')||
        request()->is('toko_banjaran/klasifikasi*')||
        request()->is('toko_banjaran/metode_pembayaran*')||
        request()->is('toko_banjaran/input*')||
        request()->is('toko_banjaran/data_stokbarangjadi*')
      
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
            <a href="{{ url('toko_banjaran/pelanggan') }}"
                class="nav-link {{ request()->is('toko_banjaran/pelanggan*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Data Pelanggan</p>
            </a>
        </li>
        @endif

        {{-- @if (auth()->check() && auth()->user()->menu['produk']) --}}
        <li class="nav-item">
            <a href="{{ url('toko_banjaran/produk') }}"
                class="nav-link {{ request()->is('toko_banjaran/produk*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Data Produk</p>
            </a>
        </li>
        {{-- @endif --}}

        {{-- @if (auth()->check() && auth()->user()->menu['toko']) --}}
        <li class="nav-item">
            <a href="{{ url('toko_banjaran/toko') }}"
                class="nav-link {{ request()->is('toko_banjaran/toko*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Data Toko</p>
            </a>
        </li>
        {{-- @endif --}}

          {{-- @if (auth()->check() && auth()->user()->menu['toko']) --}}
          <li class="nav-item">
            <a href="{{ url('toko_banjaran/stok_tokobanjaran') }}"
                class="nav-link {{ request()->is('toko_banjaran/stok_tokobanjaran*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Data Stok Produk</p>
            </a>
        </li>
        {{-- @endif --}}    


    </ul>
</li>

<li
    class="nav-item {{ request()->is('toko_banjaran/pemesanan_produk*') ||
    request()->is('toko_banjaran/penjualan_produk*') ||
    request()->is('toko_banjaran/hargajual*') || 
    request()->is('toko_banjaran/stok_barangjadi*')||  
    request()->is('toko_banjaran/permintaan_produk*')|| 
    request()->is('toko_banjaran/pengiriman_barangjadi*')|| 
    request()->is('toko_banjaranreturn_barangjadi*')|| 
    request()->is('toko_banjaran/pemindahan_tokobanjaran*') 
    // request()->is('toko_banjaran/input*')
  
        ? 'menu-open'
        : '' }}">
    <a href="#"
        class="nav-link {{ request()->is('toko_banjaran/pemesanan_produk*') ||
        request()->is('toko_banjaran/penjualan_produk*') ||
        request()->is('toko_banjaran/hargajual*') || 
        request()->is('toko_banjaran/stok_barangjadi*')||  
        request()->is('toko_banjaran/permintaan_produk*')||  
        request()->is('toko_banjaran/pengiriman_barangjadi*')||  
        request()->is('toko_banjaran/retur_barangjadi*')||  
        request()->is('toko_banjaran/pemindahan_tokobanjaran*')  
        // request()->is('toko_banjaran/input*')
      
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
            <a href="{{ url('toko_banjaran/pemesanan_produk') }}"
                class="nav-link {{ request()->is('toko_banjaran/pemesanan_produk*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Pemesanan Produk</p>
            </a>
        </li>
        {{-- @endif --}}

        {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
        <li class="nav-item">
            <a href="{{ url('toko_banjaran/penjualan_produk') }}"
                class="nav-link {{ request()->is('toko_banjaran/penjualan_produk*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Penjualan Produk</p>
            </a>
        </li>
        {{-- @endif --}}
   

          {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
          <li class="nav-item">
            <a href="{{ url('toko_banjaran/permintaan_produk') }}"
                class="nav-link {{ request()->is('toko_banjaran/permintaan_produk*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Permintaan Produk</p>
            </a>
        </li>
        {{-- @endif --}}

         {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
         <li class="nav-item">
            <a href="{{ url('toko_banjaran/stok_tokobanjaran') }}"
                class="nav-link {{ request()->is('toko_banjaran/stok_tokobanjaran*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Stok Produk</p>
            </a>
        </li>
        {{-- @endif --}}

            {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
            <li class="nav-item">
                <a href="{{ url('toko_banjaran/retur_tokobanjaran') }}"
                    class="nav-link {{ request()->is('toko_banjaran/retur_tokobanjaran*') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                    <p style="font-size: 14px;">Retur Toko Banjaran</p>
                </a>
            </li>
            {{-- @endif --}}
        
        {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
        <li class="nav-item">
            <a href="{{ url('toko_banjaran/pemindahan_tokobanjaran') }}"
                class="nav-link {{ request()->is('toko_banjaran/pemindahan_tokobanjaran*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Pemindahan Produk</p>
            </a>
        </li>
        {{-- @endif --}}
    </ul>
</li>

<li
    class="nav-item {{ request()->is('toko_banjaran/inquery_pemesananproduk*') ||
    request()->is('toko_banjaran/inquery_penjualanproduk*')|| 
    request()->is('toko_banjaran/inquery_perubahanharga*')|| 
    request()->is('toko_banjaran/inquery_permintaanproduk*')|| 
    request()->is('toko_banjaran/inquery_stokbarangjadi*')|| 
    request()->is('toko_banjaran/pengiriman_tokobanjaran*')|| 
    request()->is('toko_banjaran/inquery_pemindahanbanjaran*')|| 
    request()->is('toko_banjaran/inquery_pengirimanbarangjadi*') 
    // request()->is('toko_banjaran/input*')
  
        ? 'menu-open'
        : '' }}">
    <a href="#"
        class="nav-link {{ request()->is('toko_banjaran/inquery_pemesananproduk*') ||
        request()->is('toko_banjaran/inquery_penjualanproduk*')||
        request()->is('toko_banjaran/inquery_perubahanharga*')||
        request()->is('toko_banjaran/inquery_permintaanproduk*')||
        request()->is('toko_banjaran/inquery_stokbarangjadi*')||
        request()->is('toko_banjaran/pengiriman_tokobanjaran*')||
        request()->is('toko_banjaran/inquery_pemindahanbanjaran*')||
        request()->is('toko_banjaran/inquerypengirimanbarangjadi*')
        // request()->is('toko_banjaran/input*')
      
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
            <a href="{{ url('toko_banjaran/inquery_pemesananproduk') }}"
                class="nav-link {{ request()->is('toko_banjaran/inquery_pemesananproduk*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Inquery Pemesanan Produk</p>
            </a>
        </li>
        {{-- @endif --}}

        {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
        <li class="nav-item">
            <a href="{{ url('toko_banjaran/inquery_penjualanproduk') }}"
                class="nav-link {{ request()->is('toko_banjaran/inquery_penjualanproduk*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Inquery Penjualan Produk</p>
            </a>
        </li>
        {{-- @endif --}}

        {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
        <li class="nav-item">
            <a href="{{ url('toko_banjaran/inquery_permintaanproduk') }}"
                class="nav-link {{ request()->is('toko_banjaran/inquery_permintaanproduk*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Inquery Permintaan Produk</p>
            </a>
        </li>
        {{-- @endif --}}

        {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
        <li class="nav-item">
            <a href="{{ url('toko_banjaran/pengiriman_tokobanjaran') }}"
                class="nav-link {{ request()->is('toko_banjaran/pengiriman_tokobanjaran*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Inquery Pengiriman Banjaran</p>
            </a>
        </li>
        {{-- @endif --}}
        {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
        <li class="nav-item">
            <a href="{{ url('toko_banjaran/inquery_returbanjaran') }}"
                class="nav-link {{ request()->is('toko_banjaran/inquery_returbanjaran*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Inquery Retur Banjaran</p>
            </a>
        </li>
        {{-- @endif --}}


          {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
          <li class="nav-item">
            <a href="{{ url('toko_banjaran/inquery_pemindahanbanjaran') }}"
                class="nav-link {{ request()->is('toko_banjaran/inquery_pemindahanbanjaran*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Inquery Pemindahan Produk</p>
            </a>
        </li>
        {{-- @endif --}}

    </ul>
</li>

<li
    class="nav-item {{ request()->is('toko_banjaran/laporan_pemesananproduk*') ||
    request()->is('toko_banjaran/laporan_penjualanproduk*')  ||
    request()->is('toko_banjaran/laporan_perubahanharga*')||
    request()->is('toko_banjaran/laporan_permintaanproduk*')||
    request()->is('toko_banjaran/laporan_pengirimanproduk*')||
    request()->is('toko_banjaran/laporan_returbanjaran*')||
    request()->is('toko_banjaran/laporan_pemindahanbanjaran*')||
    request()->is('toko_banjaran/laporan_stokbarangjadi*')||
    request()->is('toko_banjaran/laporan_pengirimanbarangjadi*')
    // request()->is('toko_banjaran/input*')
  
        ? 'menu-open'
        : '' }}">
    <a href="#"
        class="nav-link {{ request()->is('toko_banjaran/laporan_pemesananproduk*') ||
        request()->is('toko_banjaran/laporan_penjualanproduk*')|| 
        request()->is('toko_banjaran/laporan_perubahanharga*') ||
        request()->is('toko_banjaran/laporan_permintaanproduk*')|| 
        request()->is('toko_banjaran/laporan_pengirimanproduk*')|| 
        request()->is('toko_banjaran/laporan_returbanjaran*')|| 
        request()->is('toko_banjaran/laporan_pemindahanbanjaran*')|| 
        request()->is('toko_banjaran/laporan_stokbarangjadi*')|| 
        request()->is('toko_banjaran/laporan_spengirimanarangjadi*') 
        // request()->is('toko_banjaran/input*')
      
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
            <a href="{{ url('toko_banjaran/laporan_pemesananproduk') }}"
                class="nav-link {{ request()->is('toko_banjaran/laporan_pemesananproduk*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Laporan Pemesanan Produk</p>
            </a>
        </li>
        {{-- @endif --}}

        {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
        <li class="nav-item">
            <a href="{{ url('toko_banjaran/laporan_penjualanproduk') }}"
                class="nav-link {{ request()->is('toko_banjaran/laporan_penjualanproduk*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Laporan Penjualan Produk</p>
            </a>
        </li>
        {{-- @endif --}}

              {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
              <li class="nav-item">
                <a href="{{ url('toko_banjaran/laporan_permintaanproduk') }}"
                    class="nav-link {{ request()->is('toko_banjaran/laporan_permintaanproduk*') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                    <p style="font-size: 14px;">Laporan Permintaan Produk</p>
                </a>
            </li>
            {{-- @endif --}}

            {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
              <li class="nav-item">
                <a href="{{ url('toko_banjaran/laporan_pengirimanproduk') }}"
                    class="nav-link {{ request()->is('toko_banjaran/laporan_pengirimanproduk*') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                    <p style="font-size: 14px;">Laporan Pengiriman Produk</p>
                </a>
            </li>
            {{-- @endif --}}

              {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
              <li class="nav-item">
                <a href="{{ url('toko_banjaran/laporan_returbanjaran') }}"
                    class="nav-link {{ request()->is('toko_banjaran/laporan_returbanjaran*') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                    <p style="font-size: 14px;">Laporan Retur Produk</p>
                </a>
            </li>
            {{-- @endif --}}

            {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
              <li class="nav-item">
                <a href="{{ url('toko_banjaran/laporan_pemindahanbanjaran') }}"
                    class="nav-link {{ request()->is('toko_banjaran/laporan_pemindahanbanjaran*') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                    <p style="font-size: 13px;">Laporan Pemindahan Produk</p>
                </a>
            </li>
            {{-- @endif --}}

    </ul>
</li>






<li class="nav-header">Profile</li>
<li class="nav-item">
    <a href="{{ url('toko_banjaran/profile') }}" class="nav-link {{ request()->is('toko_banjaran/profile') ? 'active' : '' }}">
        <i class="nav-icon fas fa-user-edit"></i>
        <p>Update Profile</p>
    </a>
<li class="nav-item">
    <a href="#" data-toggle="modal" data-target="#modalLogout" class="nav-link">
        <i class="nav-icon fas fa-sign-out-alt"></i>
        <p>Logout</p>
    </a>
</li>
