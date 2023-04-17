<?php

namespace App\Core\Components\Context;

use Illuminate\Http\Request;

class AppContext
{
    private array $context = [];

    public function setSource(SourceEnum $source): void
    {
        $this->context['source'] = $source->value;
    }

    public function setRequest(Request $request): void
    {
        $this->context['method'] = $request->method();
        $this->context['url'] = $request->url();
    }

    public function setUser(int $id, string $name): void
    {
        $this->setUserContext('user', $id, $name);
    }

    public function setAdministrator(int $id, string $name): void
    {
        $this->setUserContext('administrator', $id, $name);
    }

    public function set(string $key, mixed $value): void
    {
        $this->context[$key] = $value;
    }

    public function get(): array
    {
        return $this->context;
    }

    private function setUserContext(string $type, int $id, string $name): void
    {
        $this->context[$type] = ['id' => $id, 'name' => $name];
    }
}
