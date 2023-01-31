<?php

namespace GTS\Services\FileStorage\UI\Site\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use GTS\Services\FileStorage\Infrastructure\Facade\ReaderFacadeInterface;
use GTS\Services\FileStorage\UI\Site\Http\Actions\GetAction;

class FileController extends BaseController
{
    public function __construct(
        private readonly ReaderFacadeInterface $readerFacade
    ) {}

    public function file(Request $request, $guid, $part = null)
    {
        return (new GetAction($this->readerFacade, $request))->handle($guid, $part);
    }
}
