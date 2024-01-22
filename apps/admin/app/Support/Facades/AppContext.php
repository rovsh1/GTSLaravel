<?php

namespace App\Admin\Support\Facades;

use App\Admin\Support\Context\ContextManager;
use Illuminate\Support\Facades\Facade;
use Sdk\Shared\Contracts\Context\ContextInterface;
use Sdk\Shared\Enum\SourceEnum;

/**
 * @method static string requestId()
 * @method static SourceEnum source()
 * @method static void setAdministrator(int $id, string $name)
 * @method static void setEntity(string $class, int $id)
 * @method static void addTag(string $tag)
 * @method static void setException(\Throwable $exception)
 * @method static void setErrorCode(int $code)
 * @method static array toArray()
 *
 * @see ContextManager
 */
class AppContext extends Facade
{
    protected static function getFacadeAccessor()
    {
        return ContextInterface::class;
    }
}
