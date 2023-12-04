<?php

namespace App\Hotel\Support\Http;

use App\Hotel\Support\View\LayoutBuilder;
use App\Shared\Support\Http\Controller as BaseController;

abstract class AbstractController extends BaseController
{
    public function callAction($method, $parameters)
    {
        $response = parent::callAction($method, $parameters);

        if ($response instanceof LayoutBuilder) {
            return $response->render();
        } else {
            return $response;
        }
    }
}
