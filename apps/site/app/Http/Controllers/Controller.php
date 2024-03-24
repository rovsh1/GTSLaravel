<?php

namespace App\Site\Http\Controllers;

use App\Admin\Support\View\Layout;
use App\Shared\Support\Http\Controller as BaseController;

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
