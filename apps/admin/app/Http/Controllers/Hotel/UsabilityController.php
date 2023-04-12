<?php

namespace App\Admin\Http\Controllers\Hotel;

use App\Admin\Components\Factory\Prototype;
use App\Admin\Http\Controllers\Controller;
use App\Admin\Models\Hotel\Hotel;
use App\Admin\Models\Hotel\Reference\Usability;
use App\Admin\Support\Facades\Prototypes;
use App\Core\Support\Http\Responses\AjaxReloadResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UsabilityController extends Controller
{
    protected Prototype $prototype;

    public function __construct()
    {
        $this->prototype = Prototypes::get($this->getPrototypeKey());
    }

    public function index(Hotel $hotel): View
    {
        return view('hotel._partials.modals.usabilities', [
            'usabilities' => Usability::all(),
            'hotelUsabilities' => $hotel->usabilities,
            'usabilitiesUrl' => $this->prototype->route('show', $hotel->id) . '/usabilities',
            'rooms' => $hotel->rooms,
        ]);
    }

    public function update(Request $request, Hotel $hotel): AjaxReloadResponse
    {
        return new AjaxReloadResponse();
    }

    private function getPrototypeKey(): string
    {
        return 'hotel';
    }
}
