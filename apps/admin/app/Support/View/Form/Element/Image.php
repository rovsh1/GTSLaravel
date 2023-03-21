<?php

namespace App\Admin\Support\View\Form\Element;

use App\Admin\View\Components\FileImage;
use App\Core\Contracts\File\FileInterface;
use Custom\Form\Element\File;
use Illuminate\Http\UploadedFile;

/**
 * @property string fileType
 * @property string|null deleteRoute
 */
class Image extends File
{
    protected array $options = [
        'inputType' => 'file',
        'deleteRoute' => 'file.delete'
    ];

    private ?FileInterface $file = null;

    public function submitValue($value)
    {
        if ($value instanceof UploadedFile) {
            parent::setValue($value);
        } elseif (is_array($value)) {
            //TODO convert to UploadedFile
            //is_uploaded_file?
            parent::setValue(new UploadedFile($value['tmpname'], $value['name']));
        } else {
            parent::setValue(null);
        }
    }

    public function setValue($value)
    {
        if ($value instanceof FileInterface && $value instanceof $this->fileType) {
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
                . ($this->deleteRoute ? '<a href="#" data-url="' . route($this->deleteRoute, ['guid' => $this->file->guid()]) . '" class="btn-remove"></a>' : '')
                . '<a href="' . $this->file->url() . '" target="_blank">' . (new FileImage($this->file))->render() . '</a>'
                . '</div>'
                . '</div>';
        }

        return $html;
    }
}
