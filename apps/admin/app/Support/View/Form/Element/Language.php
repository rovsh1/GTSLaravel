<?php

namespace App\Admin\Support\View\Form\Element;

use App\Admin\Support\Facades\Languages;
use App\Shared\Components\Locale\Language as LanguageModel;

class Language extends BaseSelect
{
    public function __construct(string $name, array $options = [])
    {
        parent::__construct($name, $options);

        $this->setItems(
            Languages::map(function (LanguageModel $l) {
                return (object)[
                    'value' => $l->code,
                    'text' => $l->name,
                ];
            })
        );
    }
}
