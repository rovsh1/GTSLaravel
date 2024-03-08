<?php

namespace App\Admin\Http\Controllers\Hotel;

use App\Admin\Components\Factory\Prototype;
use App\Admin\Http\Controllers\Controller;
use App\Admin\Models\Hotel\Hotel;
use App\Admin\Models\Hotel\Reference\Usability;
use App\Admin\Repositories\Hotel\UsabilitiesRepository;
use App\Admin\Support\Facades\Prototypes;
use App\Admin\Support\Repository\RepositoryInterface;
use App\Shared\Http\Responses\AjaxReloadResponse;
use App\Shared\Http\Responses\AjaxResponseInterface;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UsabilityController extends Controller
{
    protected Prototype $prototype;

    private RepositoryInterface $repository;

    public function __construct(UsabilitiesRepository $repository)
    {
        $this->prototype = Prototypes::get($this->getPrototypeKey());
        $this->repository = $repository;
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
        $usabilitiesData = \Arr::get($request->toArray(), 'data.usabilities', []);

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

        $this->repository->update($hotel->id, $usabilityUpdateData);

        return new AjaxReloadResponse();
    }

    private function getPrototypeKey(): string
    {
        return 'hotel';
    }
}
