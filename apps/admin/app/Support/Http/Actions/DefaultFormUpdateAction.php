<?php

namespace App\Admin\Support\Http\Actions;

use App\Admin\Exceptions\FormSubmitFailedException;
use App\Admin\Support\View\Form\Form;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;

class DefaultFormUpdateAction
{
    private Model $model;

    private ?string $failUrl = null;

    private ?string $successUrl = null;

    public function __construct(private readonly Form $form)
    {
        $this->form->method('put');
    }

//    public function model(Model $model): static
//    {
//        $this->model = $model;
//        return $this;
//    }

//    public function redirectTo(Model $model): static
//    {
//        $this->model = $model;
//        return $this;
//    }

    public function successUrl(string $url): self
    {
        $this->successUrl = $url;

        return $this;
    }

    public function failUrl(string $url): self
    {
        $this->failUrl = $url;

        return $this;
    }

    /**
     * @throws FormSubmitFailedException
     */
    public function handle(Model $model): RedirectResponse
    {
        [$failUrl, $successUrl] = $this->bootDefaultUrls();

        $this->form->trySubmit($failUrl);

        $model->update($this->form->getData());

        return redirect($successUrl);
    }

    private function bootDefaultUrls(): array
    {
        $failUrl = $this->failUrl ?? request()->url() . '/edit';

        $successUrl = $this->successUrl;
        if ($successUrl === null) {
            $route = request()->route();
            $params = $route->parameters();
            array_pop($params);
            $successUrl = route(str_replace('.update', '.index', $route->getName()), $params);
        }

        return [$failUrl, $successUrl];
    }
}
