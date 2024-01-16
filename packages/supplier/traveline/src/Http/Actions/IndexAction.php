<?php

namespace Pkg\Supplier\Traveline\Http\Actions;

use Illuminate\Http\Request;
use Sdk\Module\Contracts\Support\ContainerInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class IndexAction
{
    public function __construct(
        private readonly ContainerInterface $container
    ) {}

    public function handle(Request $request)
    {
        $action = ActionNameEnum::tryFrom($request->get('action'));
        if ($action === null) {
            throw new BadRequestHttpException('Unknown Traveline request');
        }
        $parsedRequest = $action->getRequest($request);
        $request->validate($parsedRequest->rules());

        return $this->container->make($action->getAction())->handle($parsedRequest);
    }
}
