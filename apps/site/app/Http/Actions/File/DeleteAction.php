<?php

namespace App\Site\Http\Actions\File;

use Custom\Framework\Contracts\PortGateway\PortGatewayInterface;

class DeleteAction
{
    public function __construct(
        private readonly PortGatewayInterface $portGateway
    ) {}

    public function handle(string $guid)
    {
        $this->portGateway->request('files/delete', ['guid' => $guid]);

        return 'ok';
    }
}
