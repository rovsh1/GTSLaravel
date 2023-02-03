<?php

namespace GTS\Shared\UI\Common\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;

class SetClientTimezone
{
    protected $defaultTimezone = 'UTC';

    protected $cookieName = 'timezone';

    public function handle(Request $request, \Closure $next)
    {
        try {
            date_default_timezone_set($this->getClientTimezone());
        } catch (\Exception $e) {
            date_default_timezone_set($this->defaultTimezone);
        }

        $request->attributes->add(['timezone' => date_default_timezone_get()]);

        $this->bootTimezone();

        return $next($request);
    }

    protected function bootTimezone()
    {
        DB::update('SET time_zone = ?', [date('P')]);
    }

    private function getClientTimezone()
    {
        return Cookie::get($this->cookieName) ?? $this->defaultTimezone;
    }
}
