<?php

namespace App\Admin\Http\Controllers\Hotel;

use App\Admin\Exceptions\FormSubmitFailedException;
use App\Admin\Http\Controllers\Controller;
use App\Admin\Models\Hotel\Hotel;
use App\Admin\Models\Reference\Landmark;
use App\Admin\Repositories\Hotel\LandmarkRepository;
use App\Admin\Support\Distance\Calculator;
use App\Admin\Support\Distance\Point;
use App\Admin\Support\Facades\Form;
use App\Admin\Support\Facades\Layout;
use App\Admin\Support\View\Form\Form as FormContract;
use App\Admin\Support\View\Layout as LayoutContract;
use App\Shared\Http\Responses\AjaxErrorResponse;
use App\Shared\Http\Responses\AjaxReloadResponse;
use App\Shared\Http\Responses\AjaxResponseInterface;
use Illuminate\Http\Request;

class LandmarkController extends Controller
{
    public function __construct(
        private readonly Calculator $distanceCalculator,
        private readonly LandmarkRepository $repository
    ) {}

    public function create(Request $request, Hotel $hotel): LayoutContract
    {
        $form = $this->formFactory($hotel->id, $hotel->city_id)
            ->method('post')
            ->action(route('hotels.landmark.store', $hotel));

        return Layout::title('Добавить объект')
            ->view('default.dialog-form', [
                'form' => $form,
                'cancelUrl' => route('hotels.show', $hotel)
            ]);
    }

    /**
     * @throws FormSubmitFailedException
     */
    public function store(Request $request, Hotel $hotel): AjaxResponseInterface
    {
        $form = $this->formFactory($hotel->id, $hotel->city_id)
            ->method('post');

        $form->trySubmit(route('hotels.landmark.create', $hotel));

        $data = $form->getData();
        $landmarkId = $data['landmark_id'];
        $landmark = Landmark::find($landmarkId);
        $landmarkPoint = Point::buildFromCoordinates($landmark->coordinates);
        $hotelPoint = Point::buildFromCoordinates($hotel->coordinates);
        $distance = $this->distanceCalculator->getDistance($landmarkPoint, $hotelPoint);
        $this->repository->create($hotel->id, $landmarkId, $distance);

        return new AjaxReloadResponse();
    }

    public function destroy(int $hotelId, int $landmarkId): AjaxResponseInterface
    {
        try {
            $this->repository->delete($hotelId, $landmarkId);
        } catch (\Throwable $e) {
            return new AjaxErrorResponse($e->getMessage());
        }

        return new AjaxReloadResponse();
    }

    protected function formFactory(int $hotelId, int $cityId): FormContract
    {
        return Form::name('data')
            ->hidden('hotel_id', ['value' => $hotelId])
            ->select('landmark_id', [
                'label' => 'Объект',
                'required' => true,
                'items' => Landmark::whereCityId($cityId)->get(),
                'emptyItem' => '',
            ]);
    }
}
