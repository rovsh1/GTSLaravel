<?php

namespace GTS\Administrator\UI\Admin\Http\Actions\Test;

use GTS\Administrator\Infrastructure\Facade\FilesFacadeInterface;

class FilesAction
{
    public function __construct(
        private readonly FilesFacadeInterface $filesFacade

    ) {}

    public function handle(array $params = [])
    {
        $fileDto = $this->filesFacade->create();
        dd($fileDto, 1);
        //$file = $this->fileWriter->create(HotelImage::class, 3, 'ffd.jpg');
        //$file = $this->fileReader->findEntityImage(HotelImage::class, 3);
        //$x = $this->fileWriter->put($file->guid, 'fdfdff');
        //dd($file, $this->fileReader->getContents($file->guid));
    }
}
