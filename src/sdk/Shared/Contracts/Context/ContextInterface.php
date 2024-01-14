<?php

namespace Sdk\Shared\Contracts\Context;

interface ContextInterface
{
    public function requestId(): string;

    public function apiKey(): ?string;

    public function setApiKey(string $apiKey): void;

//    public function userId(): ?int;

//    public function setModule(string $module): void;

//    public function setUserId(int $id): void;

//    public function setEntity(string $class, int $id): void;

//    public function addTag(string $tag): void;

//    public function setException(\Throwable $exception): void;

//    public function setErrorCode(int $code): void;

    public function toArray(): array;
}