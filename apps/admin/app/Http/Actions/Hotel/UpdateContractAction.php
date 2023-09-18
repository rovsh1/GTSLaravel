<?php

namespace App\Admin\Http\Actions\Hotel;

use App\Admin\Support\Http\Actions\DefaultFormUpdateAction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Module\Hotel\Application\UseCase\UploadContractDocuments;
use Module\Shared\Dto\UploadedFileDto;

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