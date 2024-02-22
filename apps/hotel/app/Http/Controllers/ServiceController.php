<?php

namespace App\Hotel\Http\Controllers;

use App\Hotel\Models\Reference\Service;
use App\Hotel\Repository\ServicesRepository;
use App\Hotel\Services\HotelService;
use App\Shared\Http\Responses\AjaxReloadResponse;
use App\Shared\Http\Responses\AjaxResponseInterface;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ServiceController extends AbstractHotelController
{
    public function __construct(
        HotelService $hotelService,
        private readonly ServicesRepository $repository
    ) {
        parent::__construct($hotelService);
    }

    public function edit(): View
    {
        return view('show._modals.services', [
            'services' => Service::all(),
            'hotelServices' => $this->getHotel()->services,
            'servicesUrl' => route('hotel.services.update'),
        ]);
    }

    public function update(Request $request): AjaxResponseInterface
    {
        $servicesData = \Arr::get($request->toArray(), 'data.services');
        $servicesData = array_map(fn(array $service) => [
            'is_paid' => \Arr::has($service, 'is_paid'),
            'service_id' => (int)$service['service_id'],
            'hotel_id' => $this->getHotel()->id,
        ], $servicesData);

        $this->repository->update($this->getHotel()->id, $servicesData);

        return new AjaxReloadResponse();
    }
}
