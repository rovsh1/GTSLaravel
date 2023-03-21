<?php

namespace App\Admin\Models\ServiceProvider;

use App\Admin\Enums\ContactTypeEnum;
use Custom\Framework\Database\Eloquent\Model;

class Contact extends Model
{
    protected $table = 'service_provider_contacts';

    protected $fillable = [
        'provider_id',
        'type',
        'value',
        'description',
        'main'
    ];

    protected $casts = [
        'provider_id' => 'int',
        'type' => ContactTypeEnum::class,
        'main' => 'bool',
    ];

    public function __toString()
    {
        return (string)$this->value;
    }
}
