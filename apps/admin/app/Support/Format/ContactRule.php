<?php

namespace App\Admin\Support\Format;

use App\Admin\Enums\ContactTypeEnum;
use Gsdk\Format\Rules\RuleInterface;
use Illuminate\Database\Eloquent\Model;

class ContactRule implements RuleInterface
{
    public function format($contact, $format = null): string
    {
        $type = $contact->type;//ContactTypeEnum::from($contact->type);
        return match ($type) {
            ContactTypeEnum::PHONE => '<a href="tel:' . $contact->value . '">' . $contact->value . '</a>',
            ContactTypeEnum::EMAIL => '<a href="mailto:' . $contact->value . '">' . $contact->value . '</a>',
            ContactTypeEnum::SITE => '<a href="' . $contact->value . '" target="_blank">' . $contact->value . '</a>',
            ContactTypeEnum::ADDRESS => '<address>' . $contact->value . '</address>',
            default => $contact->value,
        };
    }
}