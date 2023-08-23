<?php

namespace Module\Shared\Infrastructure\Service\ApplicationContext\Concerns;

trait ErrorContextTrait
{
    public function setException(\Throwable $exception): void
    {
        $this->set('error.exception', $exception::class);
    }

    public function setErrorCode(int $code): void
    {
        $this->set('error.code', $code);
    }
}