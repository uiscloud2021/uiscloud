<nav class="main-header navbar
    {{ config('adminlte.classes_topnav_nav', 'navbar-expand') }}
    {{ config('adminlte.classes_topnav', 'navbar-white navbar-light') }}">

    {{-- Navbar left links --}}
    <ul class="navbar-nav">
        {{-- Left sidebar toggler link --}}
        @include('adminlte::partials.navbar.menu-item-left-sidebar-toggler')

        {{-- Configured left links --}}
        @each('adminlte::partials.navbar.menu-item', $adminlte->menu('navbar-left'), 'item')

        {{-- Custom left links --}}
        @yield('content_top_nav_left')
    </ul>

    {{-- Navbar right links --}}
    <ul class="navbar-nav ml-auto">

        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="fas fa-bell"></i>
                @if($files_block!=0)
                <span class="badge badge-warning navbar-badge">{{ $files_block }}</span>
                @endif
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                @if($files_block!=0)
                <span class="dropdown-item dropdown-header">{{ $files_block }} Archivos bloqueados</span>
                <div class="dropdown-divider"></div>
                @foreach($list_files as $lista)
                <a href="#" onclick="EditFile({{ $lista->id }})" class="dropdown-item">
                    <i class="fas fa-file mr-2"></i> {{ $lista->name }}
                    <span class="float-right text-muted text-sm">{{date("d-M-y", strtotime($lista->updated_at))}}</span>
                </a>
                <div class="dropdown-divider"></div>
                @endforeach
                <a href="{{ url('notification') }}" class="dropdown-item dropdown-footer">Ver todos los Archivos</a>
                @else
                <span class="dropdown-item dropdown-header">No se encuentran Archivos bloqueados</span>
                <div class="dropdown-divider"></div>
                @endif
            </div>
        </li>
        
        {{-- Custom right links --}}
        @yield('content_top_nav_right')

        {{-- Configured right links --}}
        @each('adminlte::partials.navbar.menu-item', $adminlte->menu('navbar-right'), 'item')

        {{-- User menu link --}}
        @if(Auth::user())
            @if(config('adminlte.usermenu_enabled'))
                @include('adminlte::partials.navbar.menu-item-dropdown-user-menu')
            @else
                @include('adminlte::partials.navbar.menu-item-logout-link')
            @endif
        @endif

        {{-- Right sidebar toggler link --}}
        @if(config('adminlte.right_sidebar'))
            @include('adminlte::partials.navbar.menu-item-right-sidebar-toggler')
        @endif

    </ul>

    

</nav>
