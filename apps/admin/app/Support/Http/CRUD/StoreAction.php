<?php

namespace App\Admin\Support\Http\CRUD;

use Illuminate\Http\Request;

use Custom\Framework\Database\Eloquent\Model;

class StoreAction
{
    public function handle(Request $request, Model $model)
    {
        $validated = $request->validated();
        $created = $model->create($validated['data']);

        if ($created) {
            return redirect()->back();
        }

        // Todo редирект на ошибку
        return redirect(route('index'));
    }
}
