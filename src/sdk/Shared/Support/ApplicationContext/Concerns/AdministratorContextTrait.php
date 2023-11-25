<?php

namespace Sdk\Shared\Support\ApplicationContext\Concerns;

trait AdministratorContextTrait
{
    public function setAdministrator(int $id, string $presentation): void
    {
        $this->set('administrator.id', $id);
        $this->set('administrator.name', $presentation);
    }

//    public function setSuperuser(): void
//    {
//        $this->set('administrator.superuser', true);
//    }

    public function administratorId(): ?int
    {
        return $this->get('administrator.id');
    }
}