<?php

namespace App\Admin\Support\Http\Actions;

use App\Admin\Exceptions\FormSubmitFailedException;
use App\Admin\Support\Facades\Breadcrumb;
use App\Admin\Support\Facades\Layout;
use App\Admin\Support\View\Form\Form;
use App\Admin\Support\View\Layout as LayoutContract;
use Illuminate\Database\Eloquent\Model;

class DefaultFormEditAction
{
    private array $options = [
        'deleteUrl' => null,
        'cancelUrl' => null,
    ];

    public function __construct(private readonly Form $form)
    {
        $this->form->method('put');
    }

    public function deletable(string $url = null): self
    {
        $this->options['deleteUrl'] = $url ?? $this->getDefaultDeleteUrl();
        return $this;
    }

    public function cancelUrl(string $url): self
    {
        $this->options['cancelUrl'] = $url;
        return $this;
    }

    /**
     * @throws FormSubmitFailedException
     */
    public function handle(Model $model): LayoutContract
    {
        Breadcrumb::add((string)$model);

        $this->form
            ->action($this->getDefaultUpdateUrl())
            ->data($model);


        return Layout::title((string)$model)
            ->view('default.form.form', [
                'form' => $this->form,
                'cancelUrl' => $this->options['cancelUrl'] ?? $this->getDefaultCancelUrl(),
                'deleteUrl' => $this->options['deleteUrl'],
            ]);
    }

    private function getDefaultUpdateUrl(): string
    {
        return str_replace('/edit', '', request()->url());
    }

    private function getDefaultCancelUrl(): string
    {
        $route = request()->route();
        $params = $route->parameters();
        array_pop($params);
        return route(str_replace('.edit', '.index', $route->getName()), $params);
    }

    private function getDefaultDeleteUrl(): string
    {
        return str_replace('/edit', '', request()->url());
    }
}
