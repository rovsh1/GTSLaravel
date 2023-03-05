<?php

namespace Gsdk\Grid\Data;

class DataFactory
{
    public static function factory($dataEntity): DataInterface
    {
        if ($dataEntity instanceof DataInterface) {
            return $dataEntity;
        } elseif (EloquentQuery::isEloquentQuery($dataEntity)) {
            return new EloquentQuery($dataEntity);
        } elseif (is_iterable($dataEntity)) {
            return new IterableData($dataEntity);
        } else {
            return new EmptyData();
        }
    }
}
