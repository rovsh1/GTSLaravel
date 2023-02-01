<?php

namespace GTS\Services\FileStorage\UI\Site\Http\Actions;

use GTS\Services\FileStorage\Infrastructure\Facade\WriterFacadeInterface;

class DeleteAction
{
    public function __construct(
        private readonly WriterFacadeInterface $writerFacade
    ) {}

    public function handle(string $guid)
    {
        $this->writerFacade->delete($guid);

        return 'ok';
    }
}
