<?php

namespace App\Api\Http\Traveline\Controllers;

use App\Api\Http\Traveline\Actions\IndexAction;
use App\Core\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TravelineController extends Controller
{
    public function index(Request $request)
    {
        return (new IndexAction())->handle($request);
    }

    public function debug(){
        $job = app(\Module\Integration\Traveline\Infrastructure\Jobs\Legacy\SyncTravelineReservationsDebug::class);
        $job->handle();
    }
}
