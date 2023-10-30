<?php

namespace Module\Support\FileStorage\Infrastructure\Service;

use Module\Support\FileStorage\Domain\Entity\File;
use Module\Support\FileStorage\Domain\ValueObject\Guid;

class DataMapper
{
    public static function modelToFile($model): File
    {
        return new File(
            new Guid($model->guid),
            $model->name,
        );
    }
}
