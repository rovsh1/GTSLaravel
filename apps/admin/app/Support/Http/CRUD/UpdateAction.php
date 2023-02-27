<?php

namespace App\Admin\Support\Http\CRUD;

use Illuminate\Http\Request;

use Custom\Framework\Database\Eloquent\Model;

class UpdateAction
{
    public function handle(Request $request, Model $model)
    {
        $validated = $request->validated();
        $updated = $model->update($validated['data']);

        if ($updated) {
            return redirect()->back();
        }

        // Todo редирект на ошибку
        return redirect(route('index'));
    }
}
