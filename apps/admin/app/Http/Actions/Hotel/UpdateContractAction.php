<?php

namespace App\Admin\Http\Actions\Hotel;

use App\Admin\Support\Http\Actions\DefaultFormUpdateAction;
use Illuminate\Database\Eloquent\Model;

class UpdateContractAction extends DefaultFormUpdateAction
{
    protected function update(Model $model, array $data): void
    {
//        $documents = $data['documents'] ?? [];
//        app(UploadContractDocuments::class)->execute(
//            $model->id,
//            array_map(fn(UploadedFile $uploadedFile) => new UploadedFileDto(
//                $uploadedFile->getClientOriginalName(),
//                $uploadedFile->get()
//            ), $documents)
//        );
        unset($data['documents']);
        $model->update($data);
    }
}
