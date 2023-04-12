<?php

namespace App\Admin\Http\Controllers\Hotel;

use App\Admin\Components\Factory\Prototype;
use App\Admin\Http\Controllers\Controller;
use App\Admin\Models\Hotel\Hotel;
use App\Admin\Models\Hotel\Reference\Service;
use App\Admin\Support\Facades\Prototypes;
use App\Core\Support\Http\Responses\AjaxReloadResponse;
use App\Core\Support\Http\Responses\AjaxResponseInterface;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ServiceController extends Controller
{
    protected Prototype $prototype;

    public function __construct()
    {
        $this->prototype = Prototypes::get($this->getPrototypeKey());
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
        return new AjaxReloadResponse();
    }

    private function getPrototypeKey(): string
    {
        return 'hotel';
    }
}
