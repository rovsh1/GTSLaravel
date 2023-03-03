<?php

namespace App\Admin\Services\Acl;

class AccessControl
{
    private readonly Rules $rules;

    public function __construct(private readonly ResourcesCollection $resources)
    {
        $this->rules = new Rules($this);
    }

    public function rules(): Rules
    {
        return $this->rules;
    }

    public function resources(): ResourcesCollection
    {
        return $this->resources;
    }

    public function getResource(string $key): ?Resource
    {
        return $this->resources->get($key);
    }

    public function isAllowed(string $resource, string $permission = null): bool
    {
        if (null === $permission) {
            [$resource, $permission] = $this->resources->parseSlug($resource);
        }

        return $this->rules->isAllowed($resource, $permission);
    }

    public function setUser($user) {
        //$this->rules
    }
}
