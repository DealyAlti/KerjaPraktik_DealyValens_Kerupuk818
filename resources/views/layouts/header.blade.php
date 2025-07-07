<header class="main-header">
    <!-- Logo -->
    <a href="{{ url('/') }}" class="logo">
        <span class="logo-mini">818</span>
        <span class="logo-lg"><b>Kerupuk 818</b></span>
    </a>

    <!-- Navbar -->
    <nav class="navbar navbar-static-top">
        <!-- Sidebar toggle -->
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>

        <!-- User Dropdown -->
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <li class="dropdown user user-menu" id="user-dropdown">
                    <a href="#" id="user-menu-toggle" class="dropdown-toggle user-dropdown-toggle">
                        <span class="hidden-xs">{{ auth()->user()->name }}</span>
                        <i class="fa fa-caret-down" style="margin-left: 5px;"></i>
                    </a>
                    <ul class="dropdown-menu user-dropdown-menu" id="user-dropdown-menu">
                        <li class="user-header">
                            <p>
                                <strong>{{ auth()->user()->name }}</strong><br>
                                <small>{{ auth()->user()->email }}</small>
                            </p>
                        </li>
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="{{ route('user.profil') }}" class="btn btn-default btn-flat">
                                    <i class="fa fa-user"></i> Profil
                                </a>
                            </div>
                            <div class="pull-right">
                                <a href="#" class="btn btn-default btn-flat"onclick="$('#logout-form').submit()">
                                    <i class="fa fa-sign-out"></i> Log Out
                                </a>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>

<form action="{{ route('logout') }}" method="POST" id="logout-form" style="display: none;">
    @csrf
</form>
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toggle = document.getElementById('user-menu-toggle');
        const dropdown = document.getElementById('user-dropdown');
        const menu = document.getElementById('user-dropdown-menu');
        const logoutBtn = document.getElementById('logout-btn');
        const logoutForm = document.getElementById('logout-form');

        toggle.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            dropdown.classList.toggle('open');
        });

        document.addEventListener('click', function (e) {
            if (!dropdown.contains(e.target)) {
                dropdown.classList.remove('open');
            }
        });

        if (logoutBtn && logoutForm) {
            logoutBtn.addEventListener('click', function(e) {
                e.preventDefault();
                logoutForm.submit(); // langsung logout tanpa tanya
            });
        }

    });
</script>
@endpush

<!-- CRITICAL CSS FIXES -->
<style>
/* === PRIORITY FIXES UNTUK DROPDOWN === */

/* 1. Reset semua pointer events dan cursor */
.main-header .navbar .nav > li > a,
.main-header .navbar .nav > li.dropdown > a,
.dropdown-toggle,
.user-dropdown-toggle {
    padding-top: 15px !important;
    padding-bottom: 15px !important;
    line-height: 20px !important;
}


/* 2. Pastikan dropdown container bisa diklik */
.dropdown,
.user-menu,
#user-dropdown {
    position: relative !important;
    cursor: pointer !important;
}

/* 3. Override semua CSS yang mungkin menghalangi */
.main-header .navbar .nav > li > a {
    display: block !important;
    padding: 15px 15px !important;
    color: white !important;
    text-decoration: none !important;
    cursor: pointer !important;
    pointer-events: auto !important;
    background: transparent !important;
    border: none !important;
    outline: none !important;
}

/* 4. Hover state */
.main-header .navbar .nav > li > a:hover,
.main-header .navbar .nav > li > a:focus {
    background: rgba(255,255,255,0.1) !important;
    color: white !important;
    outline: none !important;
}

/* 5. Dropdown menu styling */
.user-dropdown-menu {
    position: absolute !important;
    top: 100% !important;
    right: 0 !important;
    left: auto !important;
    z-index: 9999 !important;
    display: none !important;
    float: left !important;
    min-width: 280px !important;
    padding: 0 !important;
    margin: 2px 0 0 !important;
    font-size: 14px !important;
    text-align: left !important;
    list-style: none !important;
    background-color: #fff !important;
    border: 1px solid #ccc !important;
    border: 1px solid rgba(0,0,0,.15) !important;
    border-radius: 4px !important;
    box-shadow: 0 6px 12px rgba(0,0,0,.175) !important;
    background-clip: padding-box !important;
}

/* 6. Show dropdown when active */
.dropdown.open .user-dropdown-menu,
.user-menu.open .user-dropdown-menu {
    display: block !important;
}

/* 7. User header styling */
.user-header {
    padding: 10px !important;
    text-align: center !important;
    background: var(--text-green) !important;
    color: white !important;
    border-top-left-radius: 4px !important;
    border-top-right-radius: 4px !important;
}

/* 8. User footer styling */
.user-footer {
    background-color: #f9f9f9 !important;
    padding: 10px !important;
    border-bottom-left-radius: 4px !important;
    border-bottom-right-radius: 4px !important;
}

.user-footer:after {
    content: "" !important;
    display: table !important;
    clear: both !important;
}

/* 9. Caret/Arrow styling */
.fa-caret-down {
    color: white !important;
    font-size: 12px !important;
}

/* 10. Pastikan tidak ada overlay yang menghalangi */
.main-header,
.main-header .navbar,
.navbar-custom-menu,
.nav,
.navbar-nav {
    position: relative !important;
    z-index: auto !important;
}


/* 12. Override AdminLTE yang mungkin konflik */
.skin-green .main-header .navbar .nav > li > a {
    color: white !important;
}

.skin-green .main-header .navbar .nav > li > a:hover {
    background: rgba(255,255,255,0.1) !important;
}

/* 13. Pastikan element tidak tertutup overlay */
.content-wrapper,
.main-sidebar {
    z-index: 1 !important;
}

.main-header {
    z-index: 1030 !important;
}


</style>

