<?php

namespace App\Api\Http\Actions;

use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class IndexAction
{

    public function handle(Request $request)
    {
        $action = ActionNameEnum::tryFrom($request->get('action'));
        if ($action === null) {
            throw new BadRequestHttpException('Unknown Traveline request');
        }
        $parsedRequest = $action->getRequest($request);
        $request->validate($parsedRequest->rules());

        return $action->getAction()->handle($parsedRequest);
    }

}
