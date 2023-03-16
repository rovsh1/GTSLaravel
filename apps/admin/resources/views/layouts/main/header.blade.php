<header>
    <div class="logo-wrapper">
        <button id="btn-sitemap">
            <svg width="100%" height="100%" viewBox="0 0 24 24" fill="currentColor" preserveAspectRatio="xMidYMid meet" focusable="false">
                <path d="M3 18h18v-2H3v2zm0-5h18v-2H3v2zm0-7v2h18V6H3z"></path>
            </svg>
        </button>
        <a class="logo" href="/"></a>
    </div>

    <div class="header-content-wrapper">
        <div class="header-content">
            <div class="spacer"></div>
            <div class="dropdown text-end">
                <a href="#" class="btn-avatar dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    <x-file-image :file="Auth::user()->avatar()"/>
                </a>
                <ul class="dropdown-menu text-small">
                    <li><a class="dropdown-item" href="{{ route('profile') }}">Профиль</a></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{route('auth.logout')}}">
                            {{ __('auth.logout.form.button') }}
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</header>
