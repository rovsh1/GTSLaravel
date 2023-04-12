<?php

namespace App\Admin\Http\Controllers\Data;

use App\Admin\Models\Reference\City;
use App\Admin\Models\Reference\LandmarkType;
use App\Admin\Support\Distance\Calculator;
use App\Admin\Support\Distance\Point;
use App\Admin\Support\Facades\Form;
use App\Admin\Support\Facades\Format;
use App\Admin\Support\Facades\Grid;
use App\Admin\Support\Http\Controllers\AbstractPrototypeController;
use App\Admin\Support\View\Form\Form as FormContract;
use App\Admin\Support\View\Grid\Grid as GridContract;
use App\Admin\Support\View\Layout as LayoutContract;

class LandmarkController extends AbstractPrototypeController
{
    public function __construct(
        private readonly Calculator $distanceCalculator
    ) {
        parent::__construct();
    }

    protected function getPrototypeKey(): string
    {
        return 'landmark';
    }

    public function edit(int $id): LayoutContract
    {
        return parent::edit($id)
            ->addMetaName('google-maps-key', env('GOOGLE_MAPS_API_KEY'))
            ->script('administration/landmark-form')
            ->view('administration/landmark-form');
    }

    public function create(): LayoutContract
    {
        return parent::create()
            ->addMetaName('google-maps-key', env('GOOGLE_MAPS_API_KEY'))
            ->script('administration/landmark-form')
            ->view('administration/landmark-form');
    }

    protected function formFactory(): FormContract
    {
        $coordinates = isset($this->model) ? $this->model->coordinates : null;
        return Form::name('data')
            ->city('city_id', ['label' => 'Город', 'emptyItem' => '', 'required' => true])
            ->select('type_id', [
                'label' => 'Тип',
                'emptyItem' => '',
                'items' => LandmarkType::get()
            ])
            ->localeText('name', ['label' => 'Наименование', 'required' => true])
            ->text('address', ['label' => 'Адрес', 'required' => true])
            ->coordinates('coordinates', ['label' => 'Координаты', 'required' => true, 'value' => $coordinates]);
//            ->checkbox('in_city', ['label' => 'В пределах города']);
    }

    protected function gridFactory(): GridContract
    {
        return Grid::enableQuicksearch()
            ->paginator(self::GRID_LIMIT)
            ->edit($this->prototype)
            ->text('name', ['text' => 'Наименование', 'order' => true])
            ->text('city_name', ['text' => 'Город', 'order' => true])
            ->text('type_name', ['text' => 'Тип', 'order' => true])
            ->text('address', ['text' => 'Адрес'])
            ->boolean('in_city', ['text' => 'В пределах города'])
            ->number('city_distance', [
                'text' => 'Расстояние до центра',
                'order' => true,
                'renderer' => fn($r) => Format::distance($r->city_distance),
            ])
            ->orderBy('name', 'asc');
    }

    protected function saving(array $data): array
    {
        $preparedData = $data;
        $cityId = $data['city_id'];
        $city = City::find($cityId);
        $from = new Point($city->center_lat, $city->center_lon);
        $to = Point::buildFromCoordinates($data['coordinates']);
        $preparedData['center_distance'] = $this->distanceCalculator->getDistance($from, $to);
        return $preparedData;
    }
}
