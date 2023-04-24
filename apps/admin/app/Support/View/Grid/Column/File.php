<?php

namespace App\Admin\Support\View\Grid\Column;

use App\Core\Contracts\File\FileInterface;
use Custom\Framework\Database\Eloquent\Model;
use Gsdk\Grid\Column\Url;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;

class File extends Url
{
    /**
     * @param FileInterface[]|FileInterface|Collection $value
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

        $this->options['route'] = fn() => $value->url();

        return $value->name();
    }

}
