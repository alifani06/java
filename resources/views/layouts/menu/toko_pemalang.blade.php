<li class="nav-header">
    Dashboard</li>
<li class="nav-item">
    <a href="{{ url('toko_pemalang') }}" class="nav-link {{ request()->is('toko_pemalang') ? 'active' : '' }}">
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
    class="nav-item {{ request()->is('toko_pemalang/karyawan*') ||
    request()->is('toko_pemalang/pelanggan*')||
    // request()->is('toko_pemalang/barang*')||
    request()->is('toko_pemalang/produk*')||
    request()->is('toko_pemalang/toko*')||
    // request()->is('toko_pemalang/harga*')||
    request()->is('toko_pemalang/klasifikasi*')||
    request()->is('toko_pemalang/metode_pembayaran*')||
    request()->is('toko_pemalang/input*')||
    request()->is('toko_pemalang/stokpesanan_tokobanjaran*')||
    request()->is('toko_pemalang/data_stokbarangjadi*')
  
        ? 'menu-open'
        : '' }}">
    <a href="#"
        class="nav-link {{ request()->is('toko_pemalang/karyawan*') ||
        request()->is('toko_pemalang/pelanggan*')||
        // request()->is('toko_pemalang/barang*')||
        request()->is('toko_pemalang/produk*')||
        request()->is('toko_pemalang/toko*')||
        // request()->is('toko_pemalang/harga*')||
        request()->is('toko_pemalang/klasifikasi*')||
        request()->is('toko_pemalang/metode_pembayaran*')||
        request()->is('toko_pemalang/input*')||
        request()->is('toko_pemalang/stokpesanan_tokobanjaran*')||
        request()->is('toko_pemalang/data_stokbarangjadi*')
      
            ? 'active'
            : '' }}">

        <i class="nav-icon fas fa-grip-horizontal"></i>
        <p>
            <strong style="color: rgb(255, 255, 255);">MASTER</strong>
            <i class="right fas fa-angle-left"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        {{-- @if (auth()->check() && auth()->user()->menu['pelanggan']) --}}
        <li class="nav-item">
            <a href="{{ url('toko_pemalang/pelanggan') }}"
                class="nav-link {{ request()->is('toko_pemalang/pelanggan*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Data Pelanggan</p>
            </a>
        </li>
        {{-- @endif --}}

        {{-- @if (auth()->check() && auth()->user()->menu['produk']) --}}
        <li class="nav-item">
            <a href="{{ url('toko_pemalang/produk') }}"
                class="nav-link {{ request()->is('toko_pemalang/produk*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Data Produk</p>
            </a>
        </li>
        {{-- @endif --}}

          {{-- @if (auth()->check() && auth()->user()->menu['stok tokobanjaran']) --}}
          <li class="nav-item">
            <a href="{{ url('toko_pemalang/stok_tokobanjaran') }}"
                class="nav-link {{ request()->is('toko_pemalang/stok_tokobanjaran*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Data Stok Produk</p>
            </a>
            </li>
            {{-- @endif --}}

          {{-- @if (auth()->check() && auth()->user()->menu['stok tokobanjaran']) --}}
          <li class="nav-item">
            <a href="{{ url('toko_pemalang/stokpesanan_tokobanjaran') }}"
                class="nav-link {{ request()->is('toko_pemalang/stokpesanan_tokobanjaran*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Data Stok Pesanan</p>
            </a>
            </li>
            {{-- @endif --}}


    </ul>
</li>

<li
    class="nav-item {{ request()->is('toko_pemalang/pemesanan_produk*') ||
    request()->is('toko_pemalang/penjualan_produk*') ||
    request()->is('toko_pemalang/hargajual*') || 
    request()->is('toko_pemalang/stok_barangjadi*')||  
    request()->is('toko_pemalang/permintaan_produk*')|| 
    request()->is('toko_pemalang/pengiriman_barangjadi*')|| 
    request()->is('toko_pemalang/return_barangjadi*')|| 
    request()->is('toko_pemalang/pelunasan_pemesanan*')|| 
    request()->is('toko_pemalang/setoran_tokobanajran*')|| 
    request()->is('toko_pemalang/pemindahan_tokobanjaran*') 
    // request()->is('toko_pemalang/input*')
  
        ? 'menu-open'
        : '' }}">
    <a href="#"
        class="nav-link {{ request()->is('toko_pemalang/pemesanan_produk*') ||
        request()->is('toko_pemalang/penjualan_produk*') ||
        request()->is('toko_pemalang/hargajual*') || 
        request()->is('toko_pemalang/stok_barangjadi*')||  
        request()->is('toko_pemalang/permintaan_produk*')||  
        request()->is('toko_pemalang/pengiriman_barangjadi*')||  
        request()->is('toko_pemalang/retur_barangjadi*')||  
        request()->is('toko_pemalang/pelunasan_pemesanan*')||  
        request()->is('toko_pemalang/setoran_tokobanajran*')||  
        request()->is('toko_pemalang/pemindahan_tokobanjaran*')  
        // request()->is('toko_pemalang/input*')
      
            ? 'active'
            : '' }}">

        <i class="nav-icon fas fa-grip-horizontal"></i>
        <p>
            <strong style="color: rgb(255, 255, 255);">TRANSAKSI</strong>
            <i class="right fas fa-angle-left"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        {{-- @if (auth()->check() && auth()->user()->menu['pemesanan banjaran']) --}}
        <li class="nav-item">
            <a href="{{ url('toko_pemalang/pemesanan_produk') }}"
                class="nav-link {{ request()->is('toko_pemalang/pemesanan_produk*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Pemesanan Produk</p>
            </a>
        </li>
        {{-- @endif --}}

        {{-- @if (auth()->check() && auth()->user()->menu['penjualan banjaran']) --}}
        <li class="nav-item">
            <a href="{{ url('toko_pemalang/penjualan_produk') }}"
                class="nav-link {{ request()->is('toko_pemalang/penjualan_produk*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Penjualan Produk</p>
            </a>
        </li>
        {{-- @endif --}}

        {{-- @if (auth()->check() && auth()->user()->menu['pelunasan banjaran']) --}}
        <li class="nav-item">
            <a href="{{ url('toko_pemalang/pelunasan_pemesanan') }}"
                class="nav-link {{ request()->is('toko_pemalang/pelunasan_pemesanan*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Pelunasan Pemesanan</p>
            </a>
        </li>
        {{-- @endif --}}
   

          {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
          <li class="nav-item">
            <a href="{{ url('toko_pemalang/permintaan_produk') }}"
                class="nav-link {{ request()->is('toko_pemalang/permintaan_produk*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Permintaan Produk</p>
            </a>
        </li>
        {{-- @endif --}}

         {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
         <li class="nav-item">
            <a href="{{ url('toko_pemalang/stok_tokobanjaran') }}"
                class="nav-link {{ request()->is('toko_pemalang/stok_tokobanjaran*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Stok Produk</p>
            </a>
        </li>
        {{-- @endif --}}

            {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
            <li class="nav-item">
                <a href="{{ url('toko_pemalang/retur_tokobanjaran') }}"
                    class="nav-link {{ request()->is('toko_pemalang/retur_tokobanjaran*') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                    <p style="font-size: 14px;">Retur Toko Banjaran</p>
                </a>
            </li>
            {{-- @endif --}}
        
        {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
        <li class="nav-item">
            <a href="{{ url('toko_pemalang/pemindahan_tokobanjaran') }}"
                class="nav-link {{ request()->is('toko_pemalang/pemindahan_tokobanjaran*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Pemindahan Produk</p>
            </a>
        </li>
        {{-- @endif --}}
        {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
        <li class="nav-item">
            <a href="{{ url('toko_pemalang/setoran_tokobanjaran') }}"
                class="nav-link {{ request()->is('toko_pemalang/setoran_tokobanjaran*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Setoran Tunai Penjualan</p>
            </a>
        </li>
        {{-- @endif --}}
    </ul>
</li>

<li
    class="nav-item {{ request()->is('toko_pemalang/inquery_pemesananproduk*') ||
    request()->is('toko_pemalang/inquery_penjualanprodukbanjaran*')|| 
    request()->is('toko_pemalang/inquery_pelunasanbanjaran*')|| 
    request()->is('toko_pemalang/inquery_perubahanharga*')|| 
    request()->is('toko_pemalang/inquery_permintaanproduk*')|| 
    request()->is('toko_pemalang/inquery_stokbarangjadi*')|| 
    request()->is('toko_pemalang/pengiriman_tokobanjaran*')|| 
    request()->is('toko_pemalang/inquery_pemindahanbanjaran*')|| 
    request()->is('toko_pemalang/inquery_depositbanjaran*')|| 
    request()->is('toko_pemalang/inquery_setorantunaibanjaran*')|| 
    request()->is('toko_pemalang/inquery_pengirimanbarangjadi*') 
    // request()->is('toko_pemalang/input*')
  
        ? 'menu-open'
        : '' }}">
    <a href="#"
        class="nav-link {{ request()->is('toko_pemalang/inquery_pemesananproduk*') ||
        request()->is('toko_pemalang/inquery_penjualanprodukbanjaran*')||
        request()->is('toko_pemalang/inquery_pelunasanbanjaran*')||
        request()->is('toko_pemalang/inquery_perubahanharga*')||
        request()->is('toko_pemalang/inquery_permintaanproduk*')||
        request()->is('toko_pemalang/inquery_stokbarangjadi*')||
        request()->is('toko_pemalang/pengiriman_tokobanjaran*')||
        request()->is('toko_pemalang/inquery_pemindahanbanjaran*')||
        request()->is('toko_pemalang/inquery_depositbanjaran*')||
        request()->is('toko_pemalang/inquery_setorantunaibanjaran*')||
        request()->is('toko_pemalang/inquerypengirimanbarangjadi*')
        // request()->is('toko_pemalang/input*')
      
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
            <a href="{{ url('toko_pemalang/inquery_pemesananproduk') }}"
                class="nav-link {{ request()->is('toko_pemalang/inquery_pemesananproduk*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Inquery Pemesanan Produk</p>
            </a>
        </li>
        {{-- @endif --}}

        {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
        <li class="nav-item">
            <a href="{{ url('toko_pemalang/inquery_penjualanprodukbanjaran') }}"
                class="nav-link {{ request()->is('toko_pemalang/inquery_penjualanprodukbanjaran*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Inquery Penjualan Produk</p>
            </a>
        </li>
        {{-- @endif --}}

        {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
        <li class="nav-item">
            <a href="{{ url('toko_pemalang/inquery_pelunasanbanjaran') }}"
                class="nav-link {{ request()->is('toko_pemalang/inquery_pelunasanbanjaran*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 13px;">Inquery Pelunasan Pemesanan</p>
            </a>
        </li>
        {{-- @endif --}}

        {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
        <li class="nav-item">
            <a href="{{ url('toko_pemalang/inquery_permintaanproduk') }}"
                class="nav-link {{ request()->is('toko_pemalang/inquery_permintaanproduk*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Inquery Permintaan Produk</p>
            </a>
        </li>
        {{-- @endif --}}

        {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
        <li class="nav-item">
            <a href="{{ url('toko_pemalang/pengiriman_tokobanjaran') }}"
                class="nav-link {{ request()->is('toko_pemalang/pengiriman_tokobanjaran*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 13px;">Inquery Pengiriman Banjaran</p>
            </a>
        </li>
        {{-- @endif --}}
        {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
        <li class="nav-item">
            <a href="{{ url('toko_pemalang/inquery_returbanjaran') }}"
                class="nav-link {{ request()->is('toko_pemalang/inquery_returbanjaran*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Inquery Retur Banjaran</p>
            </a>
        </li>
        {{-- @endif --}}


          {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
          <li class="nav-item">
            <a href="{{ url('toko_pemalang/inquery_pemindahanbanjaran') }}"
                class="nav-link {{ request()->is('toko_pemalang/inquery_pemindahanbanjaran*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Inquery Pemindahan Produk</p>
            </a>
        </li>
        {{-- @endif --}}
          {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
          <li class="nav-item">
            <a href="{{ url('toko_pemalang/inquery_depositbanjaran') }}"
                class="nav-link {{ request()->is('toko_pemalang/inquery_depositbanjaran*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Inquery Deposit</p>
            </a>
        </li>
        {{-- @endif --}}
        
          {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
          <li class="nav-item">
            <a href="{{ url('toko_pemalang/inquery_setorantunaibanjaran') }}"
                class="nav-link {{ request()->is('toko_pemalang/inquery_setorantunaibanjaran*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Inquery Setotan Tunai</p>
            </a>
        </li>
        {{-- @endif --}}

    </ul>
</li>

<li
    class="nav-item {{ request()->is('toko_pemalang/laporan_pemesananprodukbnjr*') ||
    request()->is('toko_pemalang/laporan_penjualanproduk*')  ||
    request()->is('toko_pemalang/laporan_perubahanharga*')||
    request()->is('toko_pemalang/laporan_permintaanproduk*')||
    request()->is('toko_pemalang/laporan_pengirimanproduk*')||
    request()->is('toko_pemalang/laporan_returbanjaran*')||
    request()->is('toko_pemalang/laporan_pemindahanbanjaran*')||
    request()->is('toko_pemalang/laporan_stokbarangjadi*')||
    request()->is('toko_pemalang/laporan_stoktokobanjaran*')||
    request()->is('toko_pemalang/laporan_setorantokobanjaran*')||
    request()->is('toko_pemalang/laporan_setorantunaibanjaran*')||
    request()->is('toko_pemalang/laporan_depositbanjaran*')||
    request()->is('toko_pemalang/laporan_historibanjaran*')||
    request()->is('toko_pemalang/laporan_pengirimanbarangjadi*')
    // request()->is('toko_pemalang/input*')
  
        ? 'menu-open'
        : '' }}">
    <a href="#"
        class="nav-link {{ request()->is('toko_pemalang/laporan_pemesananprodukbnjr*') ||
        request()->is('toko_pemalang/laporan_penjualanproduk*')|| 
        request()->is('toko_pemalang/laporan_perubahanharga*') ||
        request()->is('toko_pemalang/laporan_permintaanproduk*')|| 
        request()->is('toko_pemalang/laporan_pengirimanproduk*')|| 
        request()->is('toko_pemalang/laporan_returbanjaran*')|| 
        request()->is('toko_pemalang/laporan_pemindahanbanjaran*')|| 
        request()->is('toko_pemalang/laporan_stokbarangjadi*')|| 
        request()->is('toko_pemalang/laporan_stoktokobanjaran*')|| 
        request()->is('toko_pemalang/laporan_setorantokobanjaran*')|| 
        request()->is('toko_pemalang/laporan_setorantunaibanjaran*')|| 
        request()->is('toko_pemalang/laporan_depositbanjaran*')|| 
        request()->is('toko_pemalang/laporan_historibanjaran*')|| 
        request()->is('toko_pemalang/laporan_spengirimanarangjadi*') 
        // request()->is('toko_pemalang/input*')
      
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
            <a href="{{ url('toko_pemalang/laporan_pemesananprodukbnjr') }}"
                class="nav-link {{ request()->is('toko_pemalang/laporan_pemesananprodukbnjr*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Laporan Pemesanan Produk</p>
            </a>
        </li>
        {{-- @endif --}}

        {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
        <li class="nav-item">
            <a href="{{ url('toko_pemalang/laporan_penjualanproduk') }}"
                class="nav-link {{ request()->is('toko_pemalang/laporan_penjualanproduk*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Laporan Penjualan Produk</p>
            </a>
        </li>
        {{-- @endif --}}

              {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
              <li class="nav-item">
                <a href="{{ url('toko_pemalang/laporan_permintaanproduk') }}"
                    class="nav-link {{ request()->is('toko_pemalang/laporan_permintaanproduk*') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                    <p style="font-size: 14px;">Laporan Permintaan Produk</p>
                </a>
            </li>
            {{-- @endif --}}

            {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
              <li class="nav-item">
                <a href="{{ url('toko_pemalang/laporan_pengirimantokobanjaran') }}"
                    class="nav-link {{ request()->is('toko_pemalang/laporan_pengirimantokobanjaran*') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                    <p style="font-size: 14px;">Laporan Pengiriman Produk</p>
                </a>
            </li>
            {{-- @endif --}}

              {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
              <li class="nav-item">
                <a href="{{ url('toko_pemalang/laporan_returbanjaran') }}"
                    class="nav-link {{ request()->is('toko_pemalang/laporan_returbanjaran*') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                    <p style="font-size: 14px;">Laporan Retur Produk</p>
                </a>
            </li>
            {{-- @endif --}}

            {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
              <li class="nav-item">
                <a href="{{ url('toko_pemalang/laporan_pemindahanbanjaran') }}"
                    class="nav-link {{ request()->is('toko_pemalang/laporan_pemindahanbanjaran*') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                    <p style="font-size: 13px;">Laporan Pemindahan Produk</p>
                </a>
            </li>
            {{-- @endif --}}
            {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
              <li class="nav-item">
                <a href="{{ url('toko_pemalang/laporan_stoktokobanjaran') }}"
                    class="nav-link {{ request()->is('toko_pemalang/laporan_stoktokobanjaran*') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                    <p style="font-size: 13px;">Laporan Stok Toko</p>
                </a>
            </li>
            {{-- @endif --}}

            {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
              <li class="nav-item">
                <a href="{{ url('toko_pemalang/laporan_setorantokobanjaran') }}"
                    class="nav-link {{ request()->is('toko_pemalang/laporan_setorantokobanjaran*') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                    <p style="font-size: 13px;">Laporan Setoran Kasir</p>
                </a>
            </li>
            {{-- @endif --}}

                        {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
                        <li class="nav-item">
                            <a href="{{ url('toko_pemalang/laporan_setorantunaibanjaran') }}"
                                class="nav-link {{ request()->is('toko_pemalang/laporan_setorantunaibanjaran*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                                <p style="font-size: 13px;">Laporan Setoran Tunai</p>
                            </a>
                        </li>
                        {{-- @endif --}}

            {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
              <li class="nav-item">
                <a href="{{ url('toko_pemalang/laporan_depositbanjaran') }}"
                    class="nav-link {{ request()->is('toko_pemalang/laporan_depositbanjaran*') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                    <p style="font-size: 13px;">Laporan Depsit</p>
                </a>
            </li>
            {{-- @endif --}}

            {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
              <li class="nav-item">
                <a href="{{ url('toko_pemalang/laporan_historibanjaran') }}"
                    class="nav-link {{ request()->is('toko_pemalang/laporan_historibanjaran*') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                    <p style="font-size: 13px;">Laporan Histori Barang</p>
                </a>
            </li>
            {{-- @endif --}}

    </ul>
</li>






<li class="nav-header">Profile</li>
<li class="nav-item">
    <a href="{{ url('toko_pemalang/profile') }}" class="nav-link {{ request()->is('toko_pemalang/profile') ? 'active' : '' }}">
        <i class="nav-icon fas fa-user-edit"></i>
        <p>Update Profile</p>
    </a>
<li class="nav-item">
    <a href="#" data-toggle="modal" data-target="#modalLogout" class="nav-link">
        <i class="nav-icon fas fa-sign-out-alt"></i>
        <p>Logout</p>
    </a>
</li>
