<?php

declare(strict_types=1);

namespace App\Hotel\Models;

trait HasIndexedChildren
{
    /**
     * @param array $ids
     * @param class-string $model
     * @param string $indexField
     * @return bool
     * @throws \Throwable
     */
    private function updateChildIndexes(array $ids, string $model, string $indexField = 'index'): bool
    {
        $i = 0;
        foreach ($ids as $id) {
            $child = $model::find($id);
            if (!$child) {
                throw new \Exception('Model not found', 404);
            }

            $child->update([$indexField => $i++]);
        }
        return true;
    }
}
