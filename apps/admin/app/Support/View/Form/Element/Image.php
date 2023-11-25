<?php

namespace App\Admin\Support\View\Form\Element;

use App\Admin\View\Components\FileImage;
use Gsdk\Form\Element\File;
use Illuminate\Http\UploadedFile;
use Sdk\Shared\Dto\FileDto;

/**
 * @property string fileType
 * @property string|null deleteRoute
 */
class Image extends File
{
    protected array $options = [
        'inputType' => 'file',
        //'deleteRoute' => 'file.delete'
    ];

    private ?FileDto $file = null;

    public function submitValue($value)
    {
        if ($value instanceof UploadedFile) {
            parent::setValue($value);
        } elseif (is_array($value)) {
            parent::setValue(new UploadedFile($value['tmpname'], $value['name']));
        } else {
            parent::setValue(null);
        }
    }

    public function setValue($value)
    {
        if ($value instanceof FileDto) {
            $this->file = $value;
        }
        parent::setValue(null);
    }

    public function getHtml(): string
    {
        $html = parent::getHtml();

        if ($this->file) {
            $html .= '<div class="thumbs">'
                . '<div class="thumb">'
                . ($this->deleteRoute ? '<div data-url="' . route($this->deleteRoute, ['guid' => $this->file->guid]) . '" title="Удалить файл" class="btn-remove"></div>' : '')
                . '<a href="' . $this->file->url . '" target="_blank">' . (new FileImage($this->file))->render() . '</a>'
                . '</div>'
                . '</div>';
        }

        return $html;
    }
}
