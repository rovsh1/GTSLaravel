<?php

namespace App\Admin\Services\Acl;

class ResourcesCollection
{
    private array $resources = [];

    public function __construct() {}

    public function add(Resource $resource): void
    {
        $this->resources[] = $resource;
    }

    public function has(string $id): bool
    {
        foreach ($this->resources as $resource) {
            if ($resource->id === $id)
                return true;
        }
        return false;
    }

    public function get(string $id): ?Resource
    {
        foreach ($this->resources as $resource) {
            if ($resource->id === $id)
                return $resource;
        }
        return null;
    }
}
