<?php

namespace App\Admin\Support\View\Grid\Column;

use App\Admin\Support\Facades\Format;
use Gsdk\Grid\Column\AbstractColumn;

class Enum extends AbstractColumn
{
    /** @var class-string $enumClass */
    private string $enumClass;

    protected array $options = [
        'enumClass' => null,
    ];

    /**
     * @param class-string $enumClass
     * @param string $name
     * @param array $options
     */
    public function __construct(string $name, array $options = [])
    {
        parent::__construct($name, $options);
        $this->enumClass = \Arr::get($options, 'enumClass');
        if ($this->enumClass === null) {
            throw new \Exception('Не установлен enumClass для колонки');
        }
    }

    public function renderer($row, $value): string
    {
        return Format::enum($this->enumClass::tryFrom($value));
    }
}
