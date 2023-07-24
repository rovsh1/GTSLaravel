<?php

namespace Module\Support\FileStorage\Infrastructure\Repository;

use Module\Support\FileStorage\Domain\Entity\File;

class DataMapper
{
    public static function modelToFile($model): File
    {
        return new File(
            $model->guid,
            $model->type,
            $model->extension,
            $model->entity_id,
            $model->name,
        );
    }
}
