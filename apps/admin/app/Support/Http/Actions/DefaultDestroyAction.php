<?php

namespace App\Admin\Support\Http\Actions;

use App\Core\Support\Http\Responses\AjaxErrorResponse;
use App\Core\Support\Http\Responses\AjaxRedirectResponse;
use App\Core\Support\Http\Responses\AjaxResponseInterface;
use Illuminate\Database\Eloquent\Model;

class DefaultDestroyAction
{
    private ?string $redirectUrl = null;

    public function __construct()
    {
    }

    public function handle(Model $model): AjaxResponseInterface
    {
        try {
            $model->delete();
        } catch (\Throwable $e) {
            return new AjaxErrorResponse($e->getMessage());
        }

        return new AjaxRedirectResponse($this->redirectUrl ?? $this->getDefaultRedirectUrl());
    }

    public function redirectUrl(string $url): self
    {
        $this->redirectUrl = $url;
        return $this;
    }

    private function getDefaultRedirectUrl(): string
    {
        $route = request()->route();
        $params = $route->parameters();
        array_pop($params);
        return route(str_replace('.destroy', '.index', $route->getName()), $params);
    }
}
