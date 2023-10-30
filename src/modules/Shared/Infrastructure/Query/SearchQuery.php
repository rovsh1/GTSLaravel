<?php

namespace Module\Shared\Infrastructure\Query;

class SearchQuery
{
    protected $query;

    protected array $params = [];

    public function __construct($query, mixed $paramsDto)
    {
        if (is_string($query))
            $this->query = $query::query();

        $this->params = $this->dtoToArray($paramsDto);
    }

    public function __get(string $name)
    {
        return $this->param($name);
    }

    public function __call(string $name, array $arguments)
    {
        $this->query->$name(...$arguments);
        return $this;
    }

    public function get()
    {
        $this->beforeSelect();

        $this->filter();

        $this->prepareDefault();

        return $this->query->get();
    }

    public function count(): int
    {
        $this->filter();

        return $this->query->count();
    }

    public function when(string $name, \Closure $fn): static
    {
        if (!isset($this->params[$name]) || empty($this->params[$name]))
            return $this;

        $fn($this->query, $this->params[$name]);

        return $this;
    }

    protected function param($key, $default = null)
    {
        return $this->params[$key] ?? $default;
    }

    protected function beforeSelect(): void {}

    protected function filter(): void {}

    protected function prepareDefault(): void
    {
        $this->query
            ->when($this->param('orderBy'), fn($q, $name) => $q->orderBy($name, $this->param('sortOrder') === 'desc' ? 'desc' : 'asc'))
            ->when($this->param('limit'), fn($q, $limit) => $q->limit($limit))
            ->offset($this->param('offset', 0));
    }

    protected function dtoToArray($paramsDto): array
    {
        if (empty($paramsDto))
            return [];

        return (array)$paramsDto;
    }
}
