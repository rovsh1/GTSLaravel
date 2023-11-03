<?php

namespace App\Admin\Support\View\Grid\Column;

use Gsdk\Grid\Support\AbstractColumn;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;
use Module\Shared\Dto\FileDto;
use Sdk\Module\Database\Eloquent\Model;

class File extends AbstractColumn
{
    /**
     * @param FileDto[]|FileDto|Collection $value
     * @param Model $row
     * @return string
     */
    public function formatValue($value, $row = null)
    {
        if (is_array($value) || $value instanceof Arrayable) {
            $a = [];
            foreach ($value as $file) {
                $a[] = $this->toLink($file);
            }

            return implode('<br />', $a);
        } elseif (empty($value)) {
            return '';
        } else {
            return $this->toLink($value);
        }
    }

    private function toLink(FileDto $value): string
    {
        return '<a class="download-file" href="' . $value->url . '" target="_blank" download>' . $value->name . '</a>';
    }
}
