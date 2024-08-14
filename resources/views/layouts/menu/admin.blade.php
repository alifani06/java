<style>
    .badge-small {
    font-size: 10px; /* Ukuran font badge kecil */
    margin-left: 10px; /* Jarak antara teks dan badge */
    padding: 2px 5px; /* Padding untuk badge */
    vertical-align: middle; /* Menyelaraskan badge dengan teks */
}

</style>
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
    // request()->is('admin/harga*')||
    request()->is('admin/klasifikasi*')||
    request()->is('admin/metode_pembayaran*')||
    request()->is('admin/input*')||
    request()->is('admin/data_stokbarangjadi*')||
    request()->is('admin/data_stokretur*')
  
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
        // request()->is('admin/harga*')||
        request()->is('admin/klasifikasi*')||
        request()->is('admin/metode_pembayaran*')||
        request()->is('admin/input*')||
        request()->is('admin/data_stokbarangjadi*')||
        request()->is('admin/data_stokretur*')
      
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
        {{-- <li class="nav-item">
            <a href="{{ url('admin/barang') }}"
                class="nav-link {{ request()->is('admin/barang*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Data Barang</p>
            </a>
        </li> --}}
        {{-- @endif --}}
        {{-- @if (auth()->check() && auth()->user()->menu['produk']) --}}
        <li class="nav-item">
            <a href="{{ url('admin/produk') }}"
                class="nav-link {{ request()->is('admin/produk*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Data Produk</p>
            </a>
        </li>
        {{-- @endif --}}

        {{-- @if (auth()->check() && auth()->user()->menu['toko']) --}}
        <li class="nav-item">
            <a href="{{ url('admin/toko') }}"
                class="nav-link {{ request()->is('admin/toko*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Data Toko</p>
            </a>
        </li>
        {{-- @endif --}}

        {{-- @if (auth()->check() && auth()->user()->menu['harga']) --}}
        {{-- <li class="nav-item">
            <a href="{{ url('admin/hargajual') }}"
                class="nav-link {{ request()->is('admin/hargajual*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Data Harga Jual</p>
            </a>
        </li> --}}
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
        {{-- @if (auth()->check() && auth()->user()->menu['klasifikasi']) --}}
        <li class="nav-item">
            <a href="{{ url('admin/metode_pembayaran') }}"
                class="nav-link {{ request()->is('admin/metode_pembayaran*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Data Jenis Pembayaran</p>
            </a>
        </li>
        {{-- @endif --}}

        {{-- @if (auth()->check() && auth()->user()->menu['klasifikasi']) --}}
        <li class="nav-item">
            <a href="{{ url('admin/data_stokbarangjadi') }}"
                class="nav-link {{ request()->is('admin/data_stokbarangjadi*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Data Stok Barang Jadi</p>
            </a>
        </li>
        {{-- @endif --}}

        {{-- @if (auth()->check() && auth()->user()->menu['klasifikasi']) --}}
        <li class="nav-item">
            <a href="{{ url('admin/data_stokretur') }}"
                class="nav-link {{ request()->is('admin/data_stokretur*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Data Stok Retur</p>
            </a>
        </li>
        {{-- @endif --}}

        {{-- @if (auth()->check() && auth()->user()->menu['input'])
        <li class="nav-item">
            <a href="{{ url('admin/input ') }}"
                class="nav-link {{ request()->is('admin/input *') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">input stok barang jadi</p>
            </a>
        </li>
        @endif --}}

    </ul>
</li>

<li
    class="nav-item {{ request()->is('admin/pemesanan_produk*') ||
    request()->is('admin/penjualan_produk*') ||
    request()->is('admin/hargajual*') || 
    request()->is('admin/stok_barangjadi*')||  
    request()->is('admin/permintaan_produk*')|| 
    request()->is('admin/pengiriman_barangjadi*')|| 
    request()->is('adminreturn_barangjadi*') 
    // request()->is('admin/input*')
  
        ? 'menu-open'
        : '' }}">
    <a href="#"
        class="nav-link {{ request()->is('admin/pemesanan_produk*') ||
        request()->is('admin/penjualan_produk*') ||
        request()->is('admin/hargajual*') || 
        request()->is('admin/stok_barangjadi*')||  
        request()->is('admin/permintaan_produk*')||  
        request()->is('admin/pengiriman_barangjadi*')||  
        request()->is('admin/retur_barangjadi*')  
        // request()->is('admin/input*')
      
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
            <a href="{{ url('admin/pemesanan_produk') }}"
                class="nav-link {{ request()->is('admin/pemesanan_produk*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Pemesanan Produk</p>
            </a>
        </li>
        {{-- @endif --}}

        {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
        <li class="nav-item">
            <a href="{{ url('admin/penjualan_produk') }}"
                class="nav-link {{ request()->is('admin/penjualan_produk*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Penjualan Produk</p>
            </a>
        </li>
        {{-- @endif --}}
        {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
        <li class="nav-item">
            <a href="{{ url('admin/hargajual') }}"
                class="nav-link {{ request()->is('admin/hargajual*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Perubahan Harga Jual</p>
            </a>
        </li>
        {{-- @endif --}}

          {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
          <li class="nav-item">
            <a href="{{ url('admin/permintaan_produk') }}"
                class="nav-link {{ request()->is('admin/permintaan_produk*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Permintaan Produk</p>
            </a>
        </li>
        {{-- @endif --}}
          {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
          <li class="nav-item">
            <a href="{{ url('admin/stok_barangjadi') }}"
                class="nav-link {{ request()->is('admin/stok_barangjadi*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Stok Barang Jadi</p>
            </a>
        </li>
        {{-- @endif --}}
    
          {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
          <li class="nav-item">
            <a href="{{ url('admin/pengiriman_barangjadi') }}"
                class="nav-link {{ request()->is('admin/pengiriman_barangjadi*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Pengiriman Barang Jadi</p>
            </a>
        </li>
        {{-- @endif --}}

        {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
        <li class="nav-item">
            <a href="{{ url('admin/retur_barangjadi') }}"
                class="nav-link {{ request()->is('admin/retur_barangjadi*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Retur Barang Jadi</p>
            </a>
        </li>
        {{-- @endif --}}
    </ul>
</li>


@php
    // Anggap $unpostedCount dikirim dari kontroler
    $unpostedCount = $unpostedCount ?? 0; // Pastikan default jika null
@endphp
<li
    class="nav-item {{ request()->is('admin/inquery_pemesananproduk*') ||
    request()->is('admin/inquery_penjualanproduk*')|| 
    request()->is('admin/inquery_perubahanharga*')|| 
    request()->is('admin/inquery_permintaanproduk*')|| 
    request()->is('admin/inquery_stokbarangjadi*')|| 
    request()->is('admin/inquery_pengirimanbarangjadi*') 
    // request()->is('admin/input*')
  
        ? 'menu-open'
        : '' }}">
    <a href="#"
        class="nav-link {{ request()->is('admin/inquery_pemesananproduk*') ||
        request()->is('admin/inquery_penjualanproduk*')||
        request()->is('admin/inquery_perubahanharga*')||
        request()->is('admin/inquery_permintaanproduk*')||
        request()->is('admin/inquery_stokbarangjadi*')||
        request()->is('admin/inquerypengirimankbarangjadi*')
        // request()->is('admin/input*')
      
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
            <a href="{{ url('admin/inquery_pemesananproduk') }}"
                class="nav-link {{ request()->is('admin/inquery_pemesananproduk*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Inquery Pemesanan Produk</p>
            </a>
        </li>
        {{-- @endif --}}

        {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
        <li class="nav-item">
            <a href="{{ url('admin/inquery_penjualanproduk') }}"
                class="nav-link {{ request()->is('admin/inquery_penjualanproduk*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Inquery Penjualan Produk</p>
            </a>
        </li>
        {{-- @endif --}}

        {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
        <li class="nav-item">
            <a href="{{ url('admin/inquery_perubahanharga') }}"
                class="nav-link {{ request()->is('admin/inquery_perubahanharga*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Inquery Perubahan Harga</p>
            </a>
        </li>
        {{-- @endif --}}
        
        {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
        <li class="nav-item">
            <a href="{{ url('admin/inquery_permintaanproduk') }}"
                class="nav-link {{ request()->is('admin/inquery_permintaanproduk*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Inquery Permintaan Produk
              
                </p>
            </a>
        </li>
        {{-- @endif --}}

        {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
        <li class="nav-item">
            <a href="{{ url('admin/inquery_stokbarangjadi') }}"
                class="nav-link {{ request()->is('admin/inquery_stokbarangjadi*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Inquery Stok Barang Jadi</p>
            </a>
        </li>
        {{-- @endif --}}

          {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
          <li class="nav-item">
            <a href="{{ url('admin/inquery_pengirimanbarangjadi') }}"
                class="nav-link {{ request()->is('admin/inquery_pengirimanbarangjadi*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Inquery Pengiriman Barang</p>
            </a>
        </li>
        {{-- @endif --}}

          <li class="nav-item">
            <a href="{{ url('admin/inquery_returbarangjadi') }}"
                class="nav-link {{ request()->is('admin/inquery_returbarangjadi*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Inquery Retur Barang</p>
            </a>
        </li>
        {{-- @endif --}}
    </ul>
</li>

<li
    class="nav-item {{ request()->is('admin/laporan_pemesananproduk*') ||
    request()->is('admin/laporan_penjualanproduk*')  ||
    request()->is('admin/laporan_perubahanharga*')||
    request()->is('admin/laporan_permintaanproduk*')||
    request()->is('admin/laporan_stokbarangjadi*')||
    request()->is('admin/laporan_pengirimanbarangjadi*')
    // request()->is('admin/input*')
  
        ? 'menu-open'
        : '' }}">
    <a href="#"
        class="nav-link {{ request()->is('admin/laporan_pemesananproduk*') ||
        request()->is('admin/laporan_penjualanproduk*')|| 
        request()->is('admin/laporan_perubahanharga*') ||
        request()->is('admin/laporan_permintaanproduk*')|| 
        request()->is('admin/laporan_stokbarangjadi*')|| 
        request()->is('admin/laporan_spengirimanarangjadi*') 
        // request()->is('admin/input*')
      
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
            <a href="{{ url('admin/laporan_pemesananproduk') }}"
                class="nav-link {{ request()->is('admin/laporan_pemesananproduk*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Laporan Pemesanan Produk</p>
            </a>
        </li>
        {{-- @endif --}}

        {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
        <li class="nav-item">
            <a href="{{ url('admin/laporan_penjualanproduk') }}"
                class="nav-link {{ request()->is('admin/laporan_penjualanproduk*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Laporan Penjualan Produk</p>
            </a>
        </li>
        {{-- @endif --}}

              {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
              <li class="nav-item">
                <a href="{{ url('admin/laporan_perubahanharga') }}"
                    class="nav-link {{ request()->is('admin/laporan_perubahanharga*') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                    <p style="font-size: 14px;">Laporan Perubahan Harga</p>
                </a>
            </li>
            {{-- @endif --}}

              {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
              <li class="nav-item">
                <a href="{{ url('admin/laporan_permintaanproduk') }}"
                    class="nav-link {{ request()->is('admin/laporan_permintaanproduk*') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                    <p style="font-size: 14px;">Laporan Permintaan Produk</p>
                </a>
            </li>
            {{-- @endif --}}

              {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
              <li class="nav-item">
                <a href="{{ url('admin/laporan_stokbarangjadi') }}"
                    class="nav-link {{ request()->is('admin/laporan_stokbarangjadi*') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                    <p style="font-size: 14px;">Laporan Stok Barang Jadi</p>
                </a>
            </li>
            {{-- @endif --}}

              {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
              <li class="nav-item">
                <a href="{{ url('admin/laporan_pengirimanbarangjadi') }}"
                    class="nav-link {{ request()->is('admin/laporan_pengirimanbarangjadi*') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                    <p style="font-size: 14px;">Laporan Pengiriman Barang</p>
                </a>
            </li>
            {{-- @endif --}}
    </ul>
</li>

{{-- <li
    class="nav-item {{ request()->is('admin/stok_tokoslawi*') ||
    request()->is('admin/retur_tokoslawi*')||
    request()->is('admin/pengiriman_tokoslawi*')
  
        ? 'menu-open'
        : '' }}">
    <a href="#"
        class="nav-link {{ request()->is('admin/stok_tokoslawi*') ||
        request()->is('admin/retur_tokoslawi*')|| 
        request()->is('admin/rpengirimantokoslawi*') 
      
            ? 'active'
            : '' }}">

        <i class="nav-icon fas fa-grip-horizontal"></i>
        <p>
            <strong style="color: rgb(255, 255, 255);">TOKO SLAWI</strong>
            <i class="right fas fa-angle-left"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        @if (auth()->check() && auth()->user()->menu['karyawan'])
        <li class="nav-item">
            <a href="{{ url('admin/stok_tokoslawi') }}"
                class="nav-link {{ request()->is('admin/stok_tokoslawi*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Stok Toko Slawi</p>
            </a>
        </li>
        @endif

        @if (auth()->check() && auth()->user()->menu['karyawan'])
        <li class="nav-item">
            <a href="{{ url('admin/retur_tokoslawi') }}"
                class="nav-link {{ request()->is('admin/retur_tokoslawi*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Retur Toko Slawi</p>
            </a>
        </li>
        @endif

        @if (auth()->check() && auth()->user()->menu['karyawan'])
        <li class="nav-item">
            <a href="{{ url('admin/pengiriman_tokoslawi') }}"
                class="nav-link {{ request()->is('admin/pengiriman_tokoslawi*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Pengiriman Toko Slawi</p>
            </a>
        </li>
        @endif
    </ul>
</li> --}}




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
