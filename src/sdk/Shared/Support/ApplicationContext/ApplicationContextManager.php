<?php

namespace Sdk\Shared\Support\ApplicationContext;

use Illuminate\Support\Str;
use Sdk\Shared\Contracts\Service\ApplicationContextInterface;
use Sdk\Shared\Support\ApplicationContext\Concerns\AdministratorContextTrait;
use Sdk\Shared\Support\ApplicationContext\Concerns\CommonContextTrait;
use Sdk\Shared\Support\ApplicationContext\Concerns\EntityContextTrait;
use Sdk\Shared\Support\ApplicationContext\Concerns\ErrorContextTrait;
use Sdk\Shared\Support\ApplicationContext\Concerns\EventContextTrait;
use Sdk\Shared\Support\ApplicationContext\Concerns\HttpRequestContextTrait;
use Sdk\Shared\Support\ApplicationContext\Concerns\ModuleContextTrait;
use Sdk\Shared\Support\ApplicationContext\Concerns\TagContextTrait;
use Sdk\Shared\Support\ApplicationContext\Concerns\UserContextTrait;

class ApplicationContextManager implements ApplicationContextInterface
{
    use CommonContextTrait;
    use AdministratorContextTrait;
    use EntityContextTrait;
    use ErrorContextTrait;
    use EventContextTrait;
    use HttpRequestContextTrait;
    use ModuleContextTrait;
    use UserContextTrait;
    use TagContextTrait;

    protected array $data = [];

    public function __construct()
    {
        $this->data['requestId'] = Str::orderedUuid()->toString();
    }

    public function requestId(): string
    {
        return $this->data['requestId'];
    }

    public function toArray(array $extra = []): array
    {
        return array_merge($this->data, [
            'timezone' => date_default_timezone_get(),
            'extra' => $extra
        ]);
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