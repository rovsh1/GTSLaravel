<?php

namespace GTS\Shared\UI\Admin\Http\Controllers;

use GTS\Shared\UI\Admin\View\Layout;
use GTS\Shared\UI\Common\Http\Controllers\Controller as BaseController;

class Controller extends BaseController
{
    public function callAction($method, $parameters)
    {
        $callMethod = $method;
        if (!method_exists($this, $callMethod))
            return abort(404);

        $response = call_user_func_array([$this, $callMethod], array_values($parameters));

        if (is_null($response))
            throw new \Exception('Response empty');
        elseif ($response instanceof Layout)
            return (string)$response;

        return $response;
    }
}
