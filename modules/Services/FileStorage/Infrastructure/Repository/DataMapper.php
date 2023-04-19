<?php

namespace Module\Services\FileStorage\Infrastructure\Repository;

use Module\Services\FileStorage\Domain\Entity\File;

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
