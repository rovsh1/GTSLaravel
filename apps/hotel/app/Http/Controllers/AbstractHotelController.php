<?php

declare(strict_types=1);

namespace App\Hotel\Http\Controllers;

use App\Hotel\Models\Hotel;
use App\Hotel\Services\HotelService;
use App\Hotel\Support\Http\AbstractController;

abstract class AbstractHotelController extends AbstractController
{
    public function __construct(
        private readonly HotelService $hotelService
    ) {
    }

    protected function getHotel(): Hotel
    {
        return $this->hotelService->getHotel();
    }

    protected function getPageHeader(): string
    {
        return $this->getHotel()->name;
    }
}
