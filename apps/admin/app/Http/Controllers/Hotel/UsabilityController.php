<?php

namespace App\Admin\Http\Controllers\Hotel;

use App\Admin\Components\Factory\Prototype;
use App\Admin\Http\Controllers\Controller;
use App\Admin\Models\Hotel\Hotel;
use App\Admin\Models\Hotel\Reference\Usability;
use App\Admin\Support\Facades\Prototypes;
use App\Core\Support\Http\Responses\AjaxReloadResponse;
use App\Core\Support\Http\Responses\AjaxResponseInterface;
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

    public function update(Request $request, Hotel $hotel): AjaxResponseInterface
    {
        $usabilitiesData = \Arr::get($request->toArray(), 'data.usabilities');

        $usabilityUpdateData = [];
        foreach ($usabilitiesData as $usabilityId => $roomsData) {
            foreach ($roomsData as $roomId => $value) {
                $isForAllRooms = $roomId === 'all' && (bool)$value === true;
                $hasAnotherRooms = count($roomsData) > 1;
                if ($isForAllRooms && $hasAnotherRooms) {
                    continue;
                }
                $usabilityUpdateData[] = [
                    'usability_id' => $usabilityId,
                    'hotel_id' => $hotel->id,
                    'room_id' => $isForAllRooms ? null : $roomId
                ];
            }
        }

        \DB::transaction(function () use ($hotel, $usabilityUpdateData) {
            \DB::table('hotel_usabilities')
                ->where('hotel_id', $hotel->id)
                ->delete();

            \DB::table('hotel_usabilities')->insert($usabilityUpdateData);
        });

        return new AjaxReloadResponse();
    }

    private function getPrototypeKey(): string
    {
        return 'hotel';
    }
}
