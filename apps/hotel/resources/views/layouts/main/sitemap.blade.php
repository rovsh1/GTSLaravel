<div class="sitemap-wrapper">
    <div class="sitemap">
        <div class="main-wrapper">
            <div class="btn-sitemap-toggle-switch-wrapper">
                <div class="main" id="sitemap-categories-menus">
                    {!! Layout::sidebar() !!}
                    <aside class="sidebar">
                        <div class="main-menu-wrapper">
                            <div class="sidebar-header">
                                <div class="title">{{ app(\App\Hotel\Services\HotelService::class)->getHotel()->name }}</div>
                            </div>

                            <div class="menu-wrapper">
                                <div class="sidebar-menu">

                                    <div class="group">
                                        <div class="group-items">
                                            <nav>
                                                <a href="{{ route('booking.index') }}"
                                                   class="{{ request()->routeIs('booking.index') ? 'current' : '' }}">
                                                   <i class="icon">airplane_ticket</i>
                                                    Брони
                                                </a> <a href="{{ route('quotas.index') }}"
                                                        class="{{ request()->routeIs('quotas.index') ? 'current' : '' }}">
                                                        <i class="icon">edit_calendar</i>
                                                    Квоты
                                                </a>
                                                <a href="{{ route('hotel.index') }}"
                                                   class="{{ request()->routeIs('hotel.index') ? 'current' : '' }}">
                                                   <i class="icon">description</i>
                                                    Описание
                                                </a>
                                                <a href="{{ route('rooms.index') }}"
                                                   class="{{ request()->routeIs('rooms.index') ? 'current' : '' }}">
                                                   <i class="icon">single_bed</i>
                                                    Номера
                                                </a>
                                                <a href="{{ route('images.index') }}"
                                                   class="{{ request()->routeIs('images.index') ? 'current' : '' }}">
                                                   <i class="icon">image</i>
                                                    Фотографии
                                                </a>
                                                <a href="{{ route('hotel.settings.index') }}"
                                                   class="{{ request()->routeIs('hotel.settings.index') ? 'current' : '' }}">
                                                   <i class="icon">tune</i>
                                                    Условия размещения
                                                </a>
                                                <a href="{{ route('contracts.index') }}"
                                                   class="{{ request()->routeIs('contracts.index') ? 'current' : '' }}">
                                                   <i class="icon">pending_actions</i>
                                                    Договора
                                                </a>
                                            </nav>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </aside>
                </div>
                <div class="btn-sitemap-toggle-switch-hover-effect">
                    <div class="btn-sitemap-toggle-switch">
                        <x-icon key="chevron_right"/>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
