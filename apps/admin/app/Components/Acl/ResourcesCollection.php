<?php

namespace App\Admin\Components\Acl;

class ResourcesCollection
{
    private array $resources = [];

    public function __construct() {}

    public function add(Resource $resource): void
    {
        $this->resources[] = $resource;
    }

    public function has(string $key): bool
    {
        foreach ($this->resources as $resource) {
            if ($resource->key === $key) {
                return true;
            }
        }
        return false;
    }

    public function get(string $key): ?Resource
    {
        foreach ($this->resources as $resource) {
            if ($resource->key === $key) {
                return $resource;
            }
        }
        return null;
    }
}
