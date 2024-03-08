<?php

namespace App\Admin\Http\Controllers\Hotel;

use App\Admin\Http\Controllers\Controller;
use App\Admin\Http\Requests\Hotel\GetQuotaAvailabilityRequest;
use App\Admin\Models\Hotel\Hotel;
use App\Admin\Support\Facades\Hotel\QuotaAvailabilityAdapter;
use App\Admin\Support\Facades\Layout;
use App\Admin\Support\View\Layout as LayoutContract;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Module\Hotel\Quotation\Application\Dto\QuotaDto;

class QuotaAvailabilityController extends Controller
{
    public function index(Request $request): LayoutContract
    {
        return Layout::title('Доступность')
            ->view('hotel.quota-availability.quota-availability');
    }

    public function get(GetQuotaAvailabilityRequest $request): JsonResponse
    {
        $quotas = QuotaAvailabilityAdapter::getQuotasAvailability(
            $request->getPeriod(),
            $request->getCityIds(),
            $request->getHotelIds(),
            $request->getRoomIds(),
        );

        return response()->json(
            $this->buildResponse($quotas)
        );
    }

    /**
     * @param QuotaDto[] $quotas
     * @return array
     */
    private function buildResponse(array $quotas): array
    {
        return collect($quotas)->groupBy('hotelId')->map(function (Collection $quotas, int $hotelId) {
            $quotasGroupedByDate = $quotas->groupBy(fn(QuotaDto|\Pkg\Supplier\Traveline\Dto\QuotaDto $dto) => $dto->date->format('Y-m-d'))
                ->map(fn(Collection $dtos, string $date) => [
                    'date' => $date,
                    'count_available' => $dtos->sum('countAvailable')
                ])->values();

            return [
                'hotel' => [
                    'id' => $hotelId,
                    'name' => Hotel::find($hotelId)->name,
                ],
                'quotas' => $quotasGroupedByDate
            ];
        })->values()->all();
    }
}
