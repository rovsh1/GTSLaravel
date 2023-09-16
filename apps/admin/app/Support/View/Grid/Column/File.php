<?php

namespace App\Admin\Support\View\Grid\Column;

use Gsdk\Grid\Column\Url;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;
use Module\Shared\Dto\FileDto;
use Sdk\Module\Database\Eloquent\Model;

class File extends Url
{
    /**
     * @param FileDto[]|FileDto|Collection $value
     * @param Model $row
     * @return string
     */
    public function formatValue($value, $row = null)
    {
        if (is_array($value) || $value instanceof Arrayable) {
            $value = \Arr::first($value);
        }
        if (empty($value)) {
            return '';
        }

        $this->options['route'] = fn() => $value->url;

        return $value->name;
    }

}
