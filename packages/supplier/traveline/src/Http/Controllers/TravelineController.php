<?php

namespace Pkg\Supplier\Traveline\Http\Controllers;

use App\Shared\Support\Http\Controller;
use Illuminate\Http\Request;
use Pkg\Supplier\Traveline\Http\Actions\IndexAction;
use Sdk\Module\Contracts\Support\ContainerInterface;

class TravelineController extends Controller
{
    public function __construct(
        private readonly ContainerInterface $container
    ) {}

    public function index(Request $request)
    {
        return (new IndexAction($this->container))->handle($request);
    }
}
