<?php

namespace App\Admin\Http\Controllers;

use App\Admin\Support\View\Layout;
use App\Core\Http\Controllers\Controller as BaseController;

class Controller extends BaseController
{
    public function callAction($method, $parameters)
    {
        $response = parent::callAction($method, $parameters);

        if ($response instanceof Layout) {
            return $response->render();
        } else {
            return $response;
        }
    }
}
