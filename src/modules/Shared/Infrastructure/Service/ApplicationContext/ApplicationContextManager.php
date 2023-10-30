<?php

namespace Module\Shared\Infrastructure\Service\ApplicationContext;

use Illuminate\Support\Str;
use Module\Shared\Contracts\Service\ApplicationContextInterface;

class ApplicationContextManager implements ApplicationContextInterface
{
    use Concerns\CommonContextTrait;
    use Concerns\AdministratorContextTrait;
    use Concerns\EntityContextTrait;
    use Concerns\ErrorContextTrait;
    use Concerns\EventContextTrait;
    use Concerns\HttpRequestContextTrait;
    use Concerns\ModuleContextTrait;
    use Concerns\UserContextTrait;
    use Concerns\TagContextTrait;

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
        return array_merge($this->data, ['extra' => $extra]);
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