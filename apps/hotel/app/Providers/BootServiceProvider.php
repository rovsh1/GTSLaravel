<?php

namespace App\Hotel\Providers;

use App\Hotel\Models\Administrator;
use App\Hotel\Models\Image;
use App\Hotel\Models\Room;
use App\Hotel\Services\HotelService;
use App\Hotel\Support\Context\ContextManager;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Sdk\Shared\Contracts\Context\ContextInterface;

class BootServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->register(RouteServiceProvider::class);
        $this->app->register(FormatServiceProvider::class);
        $this->app->register(ViewServiceProvider::class);
        $this->app->register(FilemanagerServiceProvider::class);
        $this->app->singleton(ContextInterface::class, ContextManager::class);
    }

    public function boot(): void
    {
        $this->app->singleton(HotelService::class);

        Gate::define('update-room', function (Administrator $administrator, Room $room) {
            return $room->hotel_id === app(HotelService::class)->getHotelId();
        });

        Gate::define('update-image', function (Administrator $administrator, Image $image) {
            return $image->hotel_id === app(HotelService::class)->getHotelId();
        });
    }
}
