<?php

namespace App\Admin\Support\Http\Controllers;

trait ShowMethodTrait
{
    public function show(int $id)
    {
        $model = $this->findModelOrFail($id);

        $this->breadcrumbs()
            ->add((string)$model);

        return $this->showResourceFactory();
    }

    abstract protected function showResourceFactory();
}
