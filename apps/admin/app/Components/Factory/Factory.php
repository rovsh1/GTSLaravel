<?php

namespace App\Admin\Components\Factory;

use Illuminate\Support\Facades\Facade;

/**
 * @method static PrototypeBuilder key(string $key)
 * @method static PrototypeBuilder category(string $category)
 * @method static PrototypeBuilder group(string $group)
 * @method static PrototypeBuilder alias(string $alias)
 * @method static PrototypeBuilder route(string $route)
 * @method static PrototypeBuilder model(string $model)
 * @method static PrototypeBuilder repository(string $group)
 * @method static PrototypeBuilder controller(string $controller, array $routeOptions = null)
 * @method static PrototypeBuilder titles(array $titles)
 * @method static PrototypeBuilder views(array $views)
 * @method static PrototypeBuilder permissions(string|array $permissions)
 * @method static PrototypeBuilder readOnly()
 * @method static PrototypeBuilder priority(int $priority)
 * @method static PrototypeBuilder registerRouteResource(string $controller, array $options = [])
 *
 * @see PrototypeBuilder
 */
class Factory extends Facade
{
    public const CATEGORY_BOOKING = 'booking';
    public const CATEGORY_HOTEL   = 'hotel';
    public const CATEGORY_FINANCE = 'finance';
    public const CATEGORY_CLIENT = 'client';
    public const CATEGORY_DATA = 'data';
    public const CATEGORY_SITE = 'site';
    public const CATEGORY_REPORTS = 'reports';
    public const CATEGORY_ADMINISTRATION = 'administration';

    public const GROUP_CONTENT = 'content';
    public const GROUP_SEO = 'seo';
    public const GROUP_LOG = 'log';
    public const GROUP_SETTINGS = 'settings';
    public const GROUP_SYSTEM = 'system';
    public const GROUP_SERVICE = 'service';
    public const GROUP_ADDITIONAL = 'additional';
    public const GROUP_ADDITIONAL_SERVICES = 'additional-services';
    public const GROUP_REFERENCE = 'reference';
    public const GROUP_NOTIFICATION = 'notification';

    protected static $cached = false;

    protected static function getFacadeAccessor()
    {
        return 'factory.builder';
    }
}
