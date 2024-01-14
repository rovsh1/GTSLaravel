<?php

namespace Sdk\Shared\Support\ApplicationContext;

use Illuminate\Support\Str;
use Sdk\Shared\Enum\SourceEnum;

abstract class AbstractContext
{
    protected array $data = [];

    public function requestId(): string
    {
        return $this->data['requestId'];
    }

    public function setApiKey(string $key): void
    {
        $this->set('apiKey', $key);
    }

    public function apiKey(): ?string
    {
        return $this->get('apiKey');
    }

    public function setSource(SourceEnum $channel): void
    {
        $this->set('source', $channel->value);
    }

    public function source(): ?SourceEnum
    {
        return SourceEnum::tryFrom($this->get('source'));
    }

    public function toArray(): array
    {
        return array_merge($this->data, [
            'timezone' => date_default_timezone_get()
        ]);
    }

    protected function generateRequestId(): void
    {
        $this->data['requestId'] = Str::orderedUuid()->toString();
    }

    protected function set(string $key, mixed $value): void
    {
        $data = &$this->data;
        $paths = explode('.', $key);
        $key = array_pop($paths);
        foreach ($paths as $k) {
            if (!isset($data[$k])) {
                $data[$k] = [];
            }
            $data = &$data[$k];
        }

        $data[$key] = $value;
    }

    protected function get(string $key): mixed
    {
        $data = $this->data;
        foreach (explode('.', $key) as $k) {
            if (!isset($data[$k])) {
                return null;
            }
            $data = $data[$k];
        }

        return $data;
    }
}