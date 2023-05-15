<?php

namespace App\Admin\Http\Controllers\Client;

use App\Admin\Exceptions\FormSubmitFailedException;
use App\Admin\Models\Client\CurrencyRate;
use App\Admin\Models\Hotel\Hotel;
use App\Admin\Models\Reference\City;
use App\Admin\Support\Facades\Form;
use App\Admin\Support\Facades\Format;
use App\Admin\Support\Facades\Grid;
use App\Admin\Support\Http\Controllers\AbstractPrototypeController;
use App\Admin\Support\View\Form\Form as FormContract;
use App\Admin\Support\View\Grid\Grid as GridContract;
use App\Admin\Support\View\Grid\SearchForm;
use Carbon\CarbonPeriod;
use Gsdk\Form\Form as FormRequest;
use Illuminate\Http\RedirectResponse;

class CurrencyRateController extends AbstractPrototypeController
{
    protected function getPrototypeKey(): string
    {
        return 'client-currency-rate';
    }

    public function store(): RedirectResponse
    {
        $form = $this->formFactory()
            ->method('post');

        $redirectUrl = $this->prototype->route('create');
        $form->trySubmit($redirectUrl);
        $this->validatePeriodIntersections($form, $redirectUrl);

        $preparedData = $this->saving($form->getData());
        $this->model = $this->repository->create($preparedData);

        $redirectUrl = $this->prototype->route('index');
        if ($this->hasShowAction()) {
            $redirectUrl = $this->prototype->route('show', $this->model);
        }
        return redirect($redirectUrl);
    }

    public function update(int $id): RedirectResponse
    {
        $this->model = $this->repository->findOrFail($id);

        $form = $this->formFactory()
            ->method('put');

        $redirectUrl = $this->prototype->route('edit', $this->model);
        $form->trySubmit($redirectUrl);
        $this->validatePeriodIntersections($form, $redirectUrl, $id);

        $preparedData = $this->saving($form->getData());
        $this->repository->update($this->model->id, $preparedData);

        $redirectUrl = $this->prototype->route('index');
        if ($this->hasShowAction()) {
            $redirectUrl = $this->prototype->route('show', $this->model);
        }
        return redirect($redirectUrl);
    }

    protected function gridFactory(): GridContract
    {
        return Grid::enableQuicksearch()
            ->setSearchForm($this->searchForm())
            ->paginator(self::GRID_LIMIT)
            ->edit($this->prototype)
            ->text('client_id', ['text' => 'Клиент', 'renderer' => fn($r, $v) => $r['client_name']])
            ->text('currency_id', ['text' => 'Валюта', 'renderer' => fn($r, $v) => $r['currency_name']])
            ->number('rate', ['text' => 'Курс', 'format' => 'number'])
            ->text('period', ['text' => 'Период', 'renderer' => fn($r, $t) => Format::period($t)]);
    }

    protected function formFactory(): FormContract
    {
        return Form::select('hotel_ids', [
            'label' => 'Отель',
            'emptyItem' => '',
            'required' => true,
            'multiple' => true,
            'groupIndex' => 'city_id',
            'groups' => City::whereHasHotel()->get(),
            'items' => Hotel::all()
        ])
            ->client('client_id', ['label' => 'Клиент', 'emptyItem' => '', 'required' => true])
            ->currency('currency_id', ['label' => 'Валюта', 'required' => true, 'emptyItem' => ''])
            ->number('rate', ['label' => 'Курс', 'required' => true])
            ->dateRange('period', ['label' => 'Период действия', 'required' => true]);
    }

    private function searchForm()
    {
        return (new SearchForm())
            ->currency('currency_id', ['label' => __('label.currency'), 'emptyItem' => ''])
            ->dateRange('start_period', ['label' => 'Дата начала'])
            ->dateRange('end_period', ['label' => 'Дата завершения']);
    }

    /**
     * @param FormRequest $form
     * @param string $redirectUrl
     * @param int|null $toSkipCurrencyRateId
     * @return void
     * @throws FormSubmitFailedException
     */
    private function validatePeriodIntersections(FormRequest $form, string $redirectUrl, ?int $toSkipCurrencyRateId = null): void {
        $clientId = $form->getData()['client_id'];
        $currencyId = $form->getData()['currency_id'];
        $clientRates = CurrencyRate::whereClientId($clientId)->get();

        /** @var CarbonPeriod $period */
        $period = $form->getData()['period'];
        foreach ($clientRates as $clientRate) {
            if ($clientRate->id === $toSkipCurrencyRateId) {
                continue;
            }
            if ($period->overlaps($clientRate->period)) {
                $exception = new FormSubmitFailedException();
                $exception->setErrors(['period' => 'Невозможно создать несколько курсов валют с пересекающимися датами для одного клиента.']);
                $exception->setRedirectUrl($redirectUrl);
                throw $exception;
            }
        }
    }
}
