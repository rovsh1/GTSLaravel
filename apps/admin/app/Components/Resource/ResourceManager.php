<?php

namespace App\Admin\Components\Resource;

class ResourceManager
{
    private array $resources = [];

    public function __construct() {}

    public function addResource(Resource $resource): void
    {
        $this->resources[] = $resource;
    }

    public function hasResource(string $key): bool
    {
        foreach ($this->resources as $resource) {
            if ($resource->key === $key) {
                return true;
            }
        }
        return false;
    }

    public function getResource(string $key): ?Resource
    {
        foreach ($this->resources as $resource) {
            if ($resource->key === $key) {
                return $resource;
            }
        }
        return null;
    }

    public function parseSlug(string $slug): array
    {
        if (str_contains($slug, ' ')) {
            return array_reverse(explode(' ', $slug, 2));
        } elseif (str_contains($slug, '.')) {
            $segments = explode('.', $slug);
            $permission = array_pop($segments);
            return [implode('.', $segments), $permission];
        } else {
            return [null, $slug];
        }
    }
}
