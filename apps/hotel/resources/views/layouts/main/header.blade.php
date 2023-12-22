<header>
    <div class="logo-wrapper">
        <button id="btn-sitemap" aria-label="Open sidebar menu">
            <svg width="100%" height="100%" viewBox="0 0 24 24" fill="currentColor" preserveAspectRatio="xMidYMid meet" focusable="false">
                <path d="M3 18h18v-2H3v2zm0-5h18v-2H3v2zm0-7v2h18V6H3z"></path>
            </svg>
        </button>
        <a class="logo" href="/" aria-label="Go to home page"></a>
    </div>

    <div class="header-content-wrapper">
        <div class="header-content">
            <div class="spacer"></div>
            <div class="dropdown text-end">
                <a href="#" class="btn-avatar" aria-label="Open user menu" data-bs-toggle="dropdown" aria-expanded="false">
                    <x-user-avatar :file="Auth::user()?->avatar" alt="User avatar"/>
                </a>
                <ul class="dropdown-menu text-small">
                    <li><a class="dropdown-item" href="{{ route('profile.index') }}">Профиль</a></li>
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