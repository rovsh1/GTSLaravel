<?php

namespace App\Hotel\Support\Facades;

use App\Hotel\Support\Context\ContextManager;
use App\Hotel\Support\View\Form\FormBuilder;
use Illuminate\Support\Facades\Facade;
use Sdk\Shared\Contracts\Service\ApplicationContextInterface;
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
 * @method static FormBuilder|null submittedForm()
 * @method static void setSubmittedForm(FormBuilder $form)
 * @method static array toArray()
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
