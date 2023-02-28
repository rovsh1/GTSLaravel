<?php

namespace App\Admin\Support\Http\CRUD;

use Illuminate\Http\Request;

use Custom\Framework\Database\Eloquent\Model;

class DeleteAction
{
    public function handle(Request $request, Model $model)
    {
        // Todo Доделать когда будет понимания, что делать со связями
        $validated = $request->validated();
        $destroyed = $model->delete();
        dd($destroyed);
    }
}
