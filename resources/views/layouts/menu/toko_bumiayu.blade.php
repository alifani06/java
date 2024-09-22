<li class="nav-header">
    Dashboard</li>
<li class="nav-item">
    <a href="{{ url('toko_bumiayu') }}" class="nav-link {{ request()->is('toko_bumiayu') ? 'active' : '' }}">
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
    class="nav-item {{ request()->is('toko_bumiayu/karyawan*') ||
    request()->is('toko_bumiayu/pelanggan*')||
    // request()->is('toko_bumiayu/barang*')||
    request()->is('toko_bumiayu/produk*')||
    request()->is('toko_bumiayu/toko*')||
    // request()->is('toko_bumiayu/harga*')||
    request()->is('toko_bumiayu/klasifikasi*')||
    request()->is('toko_bumiayu/metode_pembayaran*')||
    request()->is('toko_bumiayu/input*')||
    request()->is('toko_bumiayu/stokpesanan_tokobanjaran*')||
    request()->is('toko_bumiayu/data_stokbarangjadi*')
  
        ? 'menu-open'
        : '' }}">
    <a href="#"
        class="nav-link {{ request()->is('toko_bumiayu/karyawan*') ||
        request()->is('toko_bumiayu/pelanggan*')||
        // request()->is('toko_bumiayu/barang*')||
        request()->is('toko_bumiayu/produk*')||
        request()->is('toko_bumiayu/toko*')||
        // request()->is('toko_bumiayu/harga*')||
        request()->is('toko_bumiayu/klasifikasi*')||
        request()->is('toko_bumiayu/metode_pembayaran*')||
        request()->is('toko_bumiayu/input*')||
        request()->is('toko_bumiayu/stokpesanan_tokobanjaran*')||
        request()->is('toko_bumiayu/data_stokbarangjadi*')
      
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
            <a href="{{ url('toko_bumiayu/pelanggan') }}"
                class="nav-link {{ request()->is('toko_bumiayu/pelanggan*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Data Pelanggan</p>
            </a>
        </li>
        @endif

        @if (auth()->check() && auth()->user()->menu['produk'])
        <li class="nav-item">
            <a href="{{ url('toko_bumiayu/produk') }}"
                class="nav-link {{ request()->is('toko_bumiayu/produk*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Data Produk</p>
            </a>
        </li>
        @endif

          @if (auth()->check() && auth()->user()->menu['stok tokobanjaran'])
          <li class="nav-item">
            <a href="{{ url('toko_bumiayu/stok_tokobanjaran') }}"
                class="nav-link {{ request()->is('toko_bumiayu/stok_tokobanjaran*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Data Stok Produk</p>
            </a>
            </li>
            @endif
          {{-- @if (auth()->check() && auth()->user()->menu['stok tokobanjaran']) --}}
          <li class="nav-item">
            <a href="{{ url('toko_bumiayu/stokpesanan_tokobanjaran') }}"
                class="nav-link {{ request()->is('toko_bumiayu/stokpesanan_tokobanjaran*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Data Stok Pesanan</p>
            </a>
            </li>
            {{-- @endif --}}


    </ul>
</li>

<li
    class="nav-item {{ request()->is('toko_bumiayu/pemesanan_produk*') ||
    request()->is('toko_bumiayu/penjualan_produk*') ||
    request()->is('toko_bumiayu/hargajual*') || 
    request()->is('toko_bumiayu/stok_barangjadi*')||  
    request()->is('toko_bumiayu/permintaan_produk*')|| 
    request()->is('toko_bumiayu/pengiriman_barangjadi*')|| 
    request()->is('toko_bumiayureturn_barangjadi*')|| 
    request()->is('toko_bumiayurpelunasan_pemesanan*')|| 
    request()->is('toko_bumiayu/pemindahan_tokobanjaran*') 
    // request()->is('toko_bumiayu/input*')
  
        ? 'menu-open'
        : '' }}">
    <a href="#"
        class="nav-link {{ request()->is('toko_bumiayu/pemesanan_produk*') ||
        request()->is('toko_bumiayu/penjualan_produk*') ||
        request()->is('toko_bumiayu/hargajual*') || 
        request()->is('toko_bumiayu/stok_barangjadi*')||  
        request()->is('toko_bumiayu/permintaan_produk*')||  
        request()->is('toko_bumiayu/pengiriman_barangjadi*')||  
        request()->is('toko_bumiayu/retur_barangjadi*')||  
        request()->is('toko_bumiayu/pelunasan_pemesanan*')||  
        request()->is('toko_bumiayu/pemindahan_tokobanjaran*')  
        // request()->is('toko_bumiayu/input*')
      
            ? 'active'
            : '' }}">

        <i class="nav-icon fas fa-grip-horizontal"></i>
        <p>
            <strong style="color: rgb(255, 255, 255);">TRANSAKSI</strong>
            <i class="right fas fa-angle-left"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        @if (auth()->check() && auth()->user()->menu['pemesanan banjaran'])
        <li class="nav-item">
            <a href="{{ url('toko_bumiayu/pemesanan_produk') }}"
                class="nav-link {{ request()->is('toko_bumiayu/pemesanan_produk*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Pemesanan Produk</p>
            </a>
        </li>
        @endif

        @if (auth()->check() && auth()->user()->menu['penjualan banjaran'])
        <li class="nav-item">
            <a href="{{ url('toko_bumiayu/penjualan_produk') }}"
                class="nav-link {{ request()->is('toko_bumiayu/penjualan_produk*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Penjualan Produk</p>
            </a>
        </li>
        @endif

        @if (auth()->check() && auth()->user()->menu['pelunasan banjaran'])
        <li class="nav-item">
            <a href="{{ url('toko_bumiayu/pelunasan_pemesanan') }}"
                class="nav-link {{ request()->is('toko_bumiayu/pelunasan_pemesanan*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Pelunasan Pemesanan</p>
            </a>
        </li>
        @endif
   

          {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
          <li class="nav-item">
            <a href="{{ url('toko_bumiayu/permintaan_produk') }}"
                class="nav-link {{ request()->is('toko_bumiayu/permintaan_produk*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Permintaan Produk</p>
            </a>
        </li>
        {{-- @endif --}}

         {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
         <li class="nav-item">
            <a href="{{ url('toko_bumiayu/stok_tokobanjaran') }}"
                class="nav-link {{ request()->is('toko_bumiayu/stok_tokobanjaran*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Stok Produk</p>
            </a>
        </li>
        {{-- @endif --}}

            {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
            <li class="nav-item">
                <a href="{{ url('toko_bumiayu/retur_tokobanjaran') }}"
                    class="nav-link {{ request()->is('toko_bumiayu/retur_tokobanjaran*') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                    <p style="font-size: 14px;">Retur Toko Banjaran</p>
                </a>
            </li>
            {{-- @endif --}}
        
        {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
        <li class="nav-item">
            <a href="{{ url('toko_bumiayu/pemindahan_tokobanjaran') }}"
                class="nav-link {{ request()->is('toko_bumiayu/pemindahan_tokobanjaran*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Pemindahan Produk</p>
            </a>
        </li>
        {{-- @endif --}}
    </ul>
</li>

<li
    class="nav-item {{ request()->is('toko_bumiayu/inquery_pemesananproduk*') ||
    request()->is('toko_bumiayu/inquery_penjualanprodukbanjaran*')|| 
    request()->is('toko_bumiayu/inquery_perubahanharga*')|| 
    request()->is('toko_bumiayu/inquery_permintaanproduk*')|| 
    request()->is('toko_bumiayu/inquery_stokbarangjadi*')|| 
    request()->is('toko_bumiayu/pengiriman_tokobanjaran*')|| 
    request()->is('toko_bumiayu/inquery_pemindahanbanjaran*')|| 
    request()->is('toko_bumiayu/inquery_depositbanjaran*')|| 
    request()->is('toko_bumiayu/inquery_pengirimanbarangjadi*') 
    // request()->is('toko_bumiayu/input*')
  
        ? 'menu-open'
        : '' }}">
    <a href="#"
        class="nav-link {{ request()->is('toko_bumiayu/inquery_pemesananproduk*') ||
        request()->is('toko_bumiayu/inquery_penjualanprodukbanjaran*')||
        request()->is('toko_bumiayu/inquery_perubahanharga*')||
        request()->is('toko_bumiayu/inquery_permintaanproduk*')||
        request()->is('toko_bumiayu/inquery_stokbarangjadi*')||
        request()->is('toko_bumiayu/pengiriman_tokobanjaran*')||
        request()->is('toko_bumiayu/inquery_pemindahanbanjaran*')||
        request()->is('toko_bumiayu/inquery_depositbanjaran*')||
        request()->is('toko_bumiayu/inquerypengirimanbarangjadi*')
        // request()->is('toko_bumiayu/input*')
      
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
            <a href="{{ url('toko_bumiayu/inquery_pemesananproduk') }}"
                class="nav-link {{ request()->is('toko_bumiayu/inquery_pemesananproduk*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Inquery Pemesanan Produk</p>
            </a>
        </li>
        {{-- @endif --}}

        {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
        <li class="nav-item">
            <a href="{{ url('toko_bumiayu/inquery_penjualanprodukbanjaran') }}"
                class="nav-link {{ request()->is('toko_bumiayu/inquery_penjualanprodukbanjaran*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Inquery Penjualan Produk</p>
            </a>
        </li>
        {{-- @endif --}}

        {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
        <li class="nav-item">
            <a href="{{ url('toko_bumiayu/inquery_permintaanproduk') }}"
                class="nav-link {{ request()->is('toko_bumiayu/inquery_permintaanproduk*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Inquery Permintaan Produk</p>
            </a>
        </li>
        {{-- @endif --}}

        {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
        <li class="nav-item">
            <a href="{{ url('toko_bumiayu/pengiriman_tokobanjaran') }}"
                class="nav-link {{ request()->is('toko_bumiayu/pengiriman_tokobanjaran*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 13px;">Inquery Pengiriman Banjaran</p>
            </a>
        </li>
        {{-- @endif --}}
        {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
        <li class="nav-item">
            <a href="{{ url('toko_bumiayu/inquery_returbanjaran') }}"
                class="nav-link {{ request()->is('toko_bumiayu/inquery_returbanjaran*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Inquery Retur Banjaran</p>
            </a>
        </li>
        {{-- @endif --}}


          {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
          <li class="nav-item">
            <a href="{{ url('toko_bumiayu/inquery_pemindahanbanjaran') }}"
                class="nav-link {{ request()->is('toko_bumiayu/inquery_pemindahanbanjaran*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Inquery Pemindahan Produk</p>
            </a>
        </li>
        {{-- @endif --}}
          {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
          <li class="nav-item">
            <a href="{{ url('toko_bumiayu/inquery_depositbanjaran') }}"
                class="nav-link {{ request()->is('toko_bumiayu/inquery_depositbanjaran*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Inquery Deposit</p>
            </a>
        </li>
        {{-- @endif --}}

    </ul>
</li>

<li
    class="nav-item {{ request()->is('toko_bumiayu/laporan_pemesananprodukbnjr*') ||
    request()->is('toko_bumiayu/laporan_penjualanproduk*')  ||
    request()->is('toko_bumiayu/laporan_perubahanharga*')||
    request()->is('toko_bumiayu/laporan_permintaanproduk*')||
    request()->is('toko_bumiayu/laporan_pengirimanproduk*')||
    request()->is('toko_bumiayu/laporan_returbanjaran*')||
    request()->is('toko_bumiayu/laporan_pemindahanbanjaran*')||
    request()->is('toko_bumiayu/laporan_stokbarangjadi*')||
    request()->is('toko_bumiayu/laporan_stoktokobanjaran*')||
    request()->is('toko_bumiayu/laporan_setorantokobanjaran*')||
    request()->is('toko_bumiayu/laporan_depositbanjaran*')||
    request()->is('toko_bumiayu/laporan_pengirimanbarangjadi*')
    // request()->is('toko_bumiayu/input*')
  
        ? 'menu-open'
        : '' }}">
    <a href="#"
        class="nav-link {{ request()->is('toko_bumiayu/laporan_pemesananprodukbnjr*') ||
        request()->is('toko_bumiayu/laporan_penjualanproduk*')|| 
        request()->is('toko_bumiayu/laporan_perubahanharga*') ||
        request()->is('toko_bumiayu/laporan_permintaanproduk*')|| 
        request()->is('toko_bumiayu/laporan_pengirimanproduk*')|| 
        request()->is('toko_bumiayu/laporan_returbanjaran*')|| 
        request()->is('toko_bumiayu/laporan_pemindahanbanjaran*')|| 
        request()->is('toko_bumiayu/laporan_stokbarangjadi*')|| 
        request()->is('toko_bumiayu/laporan_stoktokobanjaran*')|| 
        request()->is('toko_bumiayu/laporan_setorantokobanjaran*')|| 
        request()->is('toko_bumiayu/laporan_depositbanjaran*')|| 
        request()->is('toko_bumiayu/laporan_spengirimanarangjadi*') 
        // request()->is('toko_bumiayu/input*')
      
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
            <a href="{{ url('toko_bumiayu/laporan_pemesananprodukbnjr') }}"
                class="nav-link {{ request()->is('toko_bumiayu/laporan_pemesananprodukbnjr*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Laporan Pemesanan Produk</p>
            </a>
        </li>
        {{-- @endif --}}

        {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
        <li class="nav-item">
            <a href="{{ url('toko_bumiayu/laporan_penjualanproduk') }}"
                class="nav-link {{ request()->is('toko_bumiayu/laporan_penjualanproduk*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Laporan Penjualan Produk</p>
            </a>
        </li>
        {{-- @endif --}}

              {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
              <li class="nav-item">
                <a href="{{ url('toko_bumiayu/laporan_permintaanproduk') }}"
                    class="nav-link {{ request()->is('toko_bumiayu/laporan_permintaanproduk*') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                    <p style="font-size: 14px;">Laporan Permintaan Produk</p>
                </a>
            </li>
            {{-- @endif --}}

            {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
              <li class="nav-item">
                <a href="{{ url('toko_bumiayu/laporan_pengirimantokobanjaran') }}"
                    class="nav-link {{ request()->is('toko_bumiayu/laporan_pengirimantokobanjaran*') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                    <p style="font-size: 14px;">Laporan Pengiriman Produk</p>
                </a>
            </li>
            {{-- @endif --}}

              {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
              <li class="nav-item">
                <a href="{{ url('toko_bumiayu/laporan_returbanjaran') }}"
                    class="nav-link {{ request()->is('toko_bumiayu/laporan_returbanjaran*') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                    <p style="font-size: 14px;">Laporan Retur Produk</p>
                </a>
            </li>
            {{-- @endif --}}

            {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
              <li class="nav-item">
                <a href="{{ url('toko_bumiayu/laporan_pemindahanbanjaran') }}"
                    class="nav-link {{ request()->is('toko_bumiayu/laporan_pemindahanbanjaran*') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                    <p style="font-size: 13px;">Laporan Pemindahan Produk</p>
                </a>
            </li>
            {{-- @endif --}}
            {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
              <li class="nav-item">
                <a href="{{ url('toko_bumiayu/laporan_stoktokobanjaran') }}"
                    class="nav-link {{ request()->is('toko_bumiayu/laporan_stoktokobanjaran*') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                    <p style="font-size: 13px;">Laporan Stok Toko</p>
                </a>
            </li>
            {{-- @endif --}}
            {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
              <li class="nav-item">
                <a href="{{ url('toko_bumiayu/laporan_setorantokobanjaran') }}"
                    class="nav-link {{ request()->is('toko_bumiayu/laporan_setorantokobanjaran*') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                    <p style="font-size: 13px;">Laporan Setoran Penjualan</p>
                </a>
            </li>
            {{-- @endif --}}
            {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
              <li class="nav-item">
                <a href="{{ url('toko_bumiayu/laporan_depositbanjaran') }}"
                    class="nav-link {{ request()->is('toko_bumiayu/laporan_depositbanjaran*') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                    <p style="font-size: 13px;">Laporan Depsit</p>
                </a>
            </li>
            {{-- @endif --}}

    </ul>
</li>






<li class="nav-header">Profile</li>
<li class="nav-item">
    <a href="{{ url('toko_bumiayu/profile') }}" class="nav-link {{ request()->is('toko_bumiayu/profile') ? 'active' : '' }}">
        <i class="nav-icon fas fa-user-edit"></i>
        <p>Update Profile</p>
    </a>
<li class="nav-item">
    <a href="#" data-toggle="modal" data-target="#modalLogout" class="nav-link">
        <i class="nav-icon fas fa-sign-out-alt"></i>
        <p>Logout</p>
    </a>
</li>
