<?php

namespace App\Admin\Http\Controllers\Hotel;

use App\Admin\Components\Factory\Prototype;
use App\Admin\Http\Controllers\Controller;
use App\Admin\Models\Hotel\Hotel;
use App\Admin\Models\Hotel\Reference\Service;
use App\Admin\Repositories\Hotel\ServicesRepository;
use App\Admin\Support\Facades\Prototypes;
use App\Admin\Support\Repository\RepositoryInterface;
use App\Shared\Http\Responses\AjaxReloadResponse;
use App\Shared\Http\Responses\AjaxResponseInterface;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ServiceController extends Controller
{
    protected Prototype $prototype;

    private RepositoryInterface $repository;

    public function __construct(ServicesRepository $repository)
    {
        $this->prototype = Prototypes::get($this->getPrototypeKey());
        $this->repository = $repository;
    }

    public function index(Hotel $hotel): View
    {
        return view('hotel._partials.modals.services', [
            'services' => Service::all(),
            'hotelServices' => $hotel->services,
            'servicesUrl' => $this->prototype->route('show', $hotel->id) . '/services',
        ]);
    }

    public function update(Request $request, Hotel $hotel): AjaxResponseInterface
    {
        $servicesData = \Arr::get($request->toArray(), 'data.services');
        $servicesData = array_map(fn(array $service) => [
            'is_paid' => \Arr::has($service, 'is_paid'),
            'service_id' => (int)$service['service_id'],
            'hotel_id' => $hotel->id,
        ], $servicesData);

        $this->repository->update($hotel->id, $servicesData);

        return new AjaxReloadResponse();
    }

    private function getPrototypeKey(): string
    {
        return 'hotel';
    }
}
