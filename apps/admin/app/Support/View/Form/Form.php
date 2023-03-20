<?php

namespace App\Admin\Support\View\Form;

use Gsdk\Form\Form as Base;
use Illuminate\Database\Eloquent\Model;

/**
 * @method self method(string $method)
 * @method self action(string $action)
 * @method self checkbox(string $name, array $options = [])
 * @method self select(string $name, array $options = [])
 * @method self hidden(string $name, array $options = [])
 * @method self text(string $name, array $options = [])
 * @method self email(string $name, array $options = [])
 * @method self phone(string $name, array $options = [])
 * @method self password(string $name, array $options = [])
 * @method self number(string $name, array $options = [])
 * @method self dateRange(string $name, array $options = [])
 * @method self currency(string $name, array $options = [])
 * @method self country(string $name, array $options = [])
 * @method self city(string $name, array $options = [])
 * @method self hotelType(string $name, array $options = [])
 * @method self hotelRating(string $name, array $options = [])
 * @method self enum(string $enumClass, string $name, array $options = [])
 * @method self hotelStatus(string $name, array $options = [])
 * @method self coordinates(string $name, array $options = [])
 * @method self client(string $name, array $options = [])
 * @method self manager(string $name, array $options = [])
 * @method self hotel(string $name, array $options = [])
 * @method self localeText(string $name, array $options = [])
 * @method self language(string $name, array $options = [])
 * @method self numRange(string $name, array $options = [])
 */
class Form extends Base
{
    public function data($data): static
    {
        if ($data instanceof Model && method_exists($data, 'isTranslatable')) {
            $model = $data;
            $data = $model->toArray();
            foreach ($this->elements as $element) {
                if ($model->isTranslatable($element->name)) {
                    $data[$element->name] = $model->getTranslations($element->name);
                }
            }
        }

        return parent::data($data);
    }
}
