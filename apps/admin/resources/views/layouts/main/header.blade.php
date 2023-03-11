<header>
    <div class="logo-wrapper">
        <button id="btn-sitemap">
            <svg width="100%" height="100%" viewBox="0 0 24 24" fill="currentColor" preserveAspectRatio="xMidYMid meet" focusable="false">
                <path d="M3 18h18v-2H3v2zm0-5h18v-2H3v2zm0-7v2h18V6H3z"></path>
            </svg>
        </button>
        <div class="logo"></div>
    </div>

    <div class="header-content-wrapper">
        <div class="header-content">
            {!! Layout::breadcrumbs() !!}
            <div class="spacer"></div>
            <div class="dropdown text-end">
                <a href="#" class="d-block link-dark text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="https://github.com/mdo.png" alt="mdo" width="32" height="32" class="rounded-circle">
                </a>
                <ul class="dropdown-menu text-small">
                    <li><a class="dropdown-item" href="#">New project...</a></li>
                    <li><a class="dropdown-item" href="#">Settings</a></li>
                    <li><a class="dropdown-item" href="#">Profile</a></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li><a class="dropdown-item" href="#">Sign out</a></li>
                </ul>
            </div>
        </div>
    </div>
</header>
