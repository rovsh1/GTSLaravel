<?php

namespace App\Admin\Support\Facades;

use App\Admin\Support\Context\ContextManager;
use App\Admin\Support\View\Form\Form;
use Illuminate\Support\Facades\Facade;
use Module\Shared\Contracts\Service\ApplicationContextInterface;
use Sdk\Shared\Enum\Context\ContextChannelEnum;
use Sdk\Shared\Enum\SourceEnum;

/**
 * @method static string requestId()
 * @method static SourceEnum source()
 * @method static void setSource(SourceEnum $source)
 * @method static void setChannel(ContextChannelEnum $channel)
 * @method static void setHttpHost(string $host)
 * @method static void setHttpUrl(string $url))
 * @method static void setHttpMethod(string $method)
 * @method static void setUserIp(string $userIp)
 * @method static void setUserAgent(string $userAgent)
 * @method static void setUser(int $id, string $name = null)
 * @method static void setAdministrator(int $id, string $name)
 * @method static void setEntity(string $class, int $id)
 * @method static void addTag(string $tag)
 * @method static void setException(\Throwable $exception)
 * @method static void setErrorCode(int $code)
 * @method static Form|null submittedForm()
 * @method static void setSubmittedForm(Form $form)
 * @method static array toArray(array $extra = [])
 *
 * @see ApplicationContextInterface
 * @see ContextManager
 */
class AppContext extends Facade
{
    protected static function getFacadeAccessor()
    {
        return ContextManager::class;
    }
}
