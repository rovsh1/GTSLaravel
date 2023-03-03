<?php

namespace App\Admin\Http\Resources\Country;

use App\Admin\Repositories\CountryRepository;

class Settings
{
    protected string $repository = CountryRepository::class;

    protected array $titles = [
        'index' => 'Страны',
        'create' => 'Новая страна'
    ];

    protected array $views = [
        'form' => 'reference.country.form'
    ];

    protected string $routePrefix = 'country';

    public function repository(): string
    {
        return $this->repository;
    }

    public function title(string ...$keys): ?string
    {
        foreach ($keys as $key) {
            if (isset($this->titles[$key])) {
                return $this->titles[$key];
            }
        }
        return null;
    }

    public function view(string ...$keys): ?string
    {
        foreach ($keys as $key) {
            if (isset($this->views[$key])) {
                return $this->views[$key];
            }
        }
        return null;
    }

    public function route(string $method, $params = []): string
    {
        return route($this->routePrefix . '.' . $method, $params);
    }
}
