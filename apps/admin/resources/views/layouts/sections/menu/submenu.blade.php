@if (isset($menu))
    <ul class="menu-sub">
        @foreach ($menu as $submenu)
            @php
                $activeClass = null;
                $active = 'active open';
                $currentRouteName = Route::currentRouteName();

                if (in_array($currentRouteName, $submenu->slug)) {
                    $activeClass = 'active open';
                }
            @endphp

            <li class="menu-item {{$activeClass}}">
                <a href="{{ isset($submenu->url) ? url($submenu->url) : 'javascript:void(0)' }}" class="{{ isset($submenu->submenu) ? 'menu-link menu-toggle' : 'menu-link' }}"
                    @if (isset($submenu->target) && !empty($submenu->target)) target="_blank" @endif>
                    @if (isset($submenu->icon))
                        <i class="{{ $submenu->icon }}"></i>
                    @endif
                    <div>{{ isset($submenu->name) ? __($submenu->name) : '' }}</div>
                </a>

                @if (isset($submenu->submenu))
                    @include('layouts.sections.menu.submenu',['menu' => $submenu->submenu])
                @endif
            </li>
        @endforeach
    </ul>
@endif
