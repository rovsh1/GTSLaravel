<?php

namespace GTS\Administrator\Domain\Service\Acl;

class AccessControl implements AccessControlInterface
{
    private readonly Rules $rules;

    private readonly ResourcesCollection $resources;

    public function __construct()
    {
        $this->rules = new Rules($this);
        $this->resources = new ResourcesCollection();
    }

    public function rules(): Rules
    {
        return $this->rules;
    }

    public function resources(): ResourcesCollection
    {
        return $this->resources;
    }

    public function isAllowed(string $resourceId, string $permission): bool
    {
        return $this->rules->isAllowed($resourceId, $permission);
    }
}
