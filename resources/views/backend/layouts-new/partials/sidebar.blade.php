<style>
    .active-title {
        color: #fff !important;
    }

    .menu-inner {
        background: #EE4D2D;
    }

    .menu-vertical .menu-item .menu-link {
        color: white;
    }
</style>

<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme"
    style="border-right: 3px solid rgb(114 113 113 / 52%);">
    <div class="app-brand demo">
        <a href="#" class="app-brand-link">
            <img src="{{ asset('assets/img/logos/logo-study-english.webp') }}" style="max-width: 40%">
            <span class="demo fw-bold ms-2" style="color: black">Study English</span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>

    @php
        $usr = Auth::guard('admin')->user();
        if ($usr != null) {
            $userRole = $usr->getRoleNames()->first();
        }
    @endphp

    <div class="menu-inner-shadow" style="background: #EE4D2D !important"></div>

    <ul class="menu-inner py-1">

        {{-- Dashboard --}}
        <li class="menu-item mb-2">
            <a href="{{ route('admin.dashboard') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home"></i>
                <div data-i18n="dashboard">Beranda</div>
            </a>
        </li>

        {{-- Topic --}}
        @if ($usr->can('topic.view'))
            <li class="menu-item mb-2">
                <a href="{{ route('topic') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-book-open"></i>
                    <div data-i18n="Topic">Topic</div>
                </a>
            </li>
        @endif

        {{-- Article --}}
        @if ($usr->can('article.view'))
            <li class="menu-item mb-2">
                <a href="{{ route('article') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-news"></i>
                    <div data-i18n="Article">Article</div>
                </a>
            </li>
        @endif

        {{-- Management Users --}}
        @if ($usr->can('admin.view') || $usr->can('role.view'))
            <li
                class="menu-item {{ Request::routeIs('admin/admins') || Request::routeIs('admin/roles') ? 'open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-user-circle"></i>
                    <div data-i18n="Layouts">Management Users</div>
                </a>

                <ul class="menu-sub">
                    <li class="menu-item {{ Request::routeIs('admin/admins') ? 'active' : '' }}">
                        <a href="{{ route('admin.admins.index') }}" class="menu-link">
                            <i class="menu-icon bx bx-user"></i>
                            <div data-i18n="Without menu"
                                style="color : {{ Request::routeIs('admin/admins') ? '#EE4D2D' : '' }}">
                                Users
                            </div>
                        </a>
                    </li>
                    <li class="menu-item {{ Request::routeIs('admin/roles') ? 'active' : '' }}">
                        <a href="{{ route('admin.roles.index') }}" class="menu-link">
                            <i class="menu-icon bx bx-lock-alt"></i>
                            <div data-i18n="Without menu"
                                style="color : {{ Request::routeIs('admin/roles') ? '#EE4D2D' : '' }}">
                                Role
                            </div>
                        </a>
                    </li>
                </ul>
            </li>
        @endif

    </ul>
</aside>
