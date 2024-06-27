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
    request()->is('admin/barang*')||
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
        request()->is('admin/barang*')||
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
        {{-- <li class="nav-item">
            <a href="{{ url('admin/kartu') }}"
                class="nav-link {{ request()->is('admin/kartu*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Data Kartu</p>
            </a>
        </li> --}}
        {{-- <li class="nav-item">
            <a href="{{ url('admin/member') }}"
                class="nav-link {{ request()->is('admin/member*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Kode Member</p>
            </a>
        </li> --}}
        @if (auth()->check() && auth()->user()->menu['barang'])
        <li class="nav-item">
            <a href="{{ url('admin/barang') }}"
                class="nav-link {{ request()->is('admin/barang*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Data Barang</p>
            </a>
        </li>
        @endif

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
    class="nav-item {{ 
    request()->is('toko/tegal*') ||
    request()->is('toko/slawi*') ||
    request()->is('toko/benjaran*') ||
    request()->is('toko/pekalongan*')||
    request()->is('toko/bumiayu*')
        ? 'menu-open'
        : '' }}">
    <a href="#"
        class="nav-link {{ 
        request()->is('toko/tegal*') ||
        request()->is('toko/slawi*') ||
        request()->is('toko/benjaran*') ||
        request()->is('toko/pekalongan*')||
        request()->is('toko/bumiayu*')
            ? 'active'
            : '' }}">

        <i class="nav-icon fas fa-grip-horizontal"></i>
        <p>
            <strong style="color: rgb(255, 255, 255);">TOKO CABANG</strong>
            <i class="right fas fa-angle-left"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="{{ url('toko/benjaran') }}"
                class="nav-link {{ request()->is('toko/karyawan*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">BENJARAN</p>
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ url('toko/bumiayu') }}" class="nav-link {{ request()->is('toko/bumiayu*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">BUMIAYU</p>
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ url('toko/pekalongan') }}" class="nav-link {{ request()->is('toko/pekalongan*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">PEKALONGAN</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ url('toko/slawi') }}"
                class="nav-link {{ request()->is('toko/slawi*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">SLAWI</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ url('toko/tegal') }}"
                class="nav-link {{ request()->is('toko/tegal*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">TEGAL</p>
            </a>
        </li>
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
