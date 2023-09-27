<?php

namespace App\Admin\Models\Supplier;

use Module\Shared\Enum\ContactTypeEnum;
use Sdk\Module\Database\Eloquent\Model;

class Contact extends Model
{
    protected $table = 'supplier_contacts';

    protected $fillable = [
        'supplier_id',
        'type',
        'value',
        'description',
        'main'
    ];

    protected $casts = [
        'supplier_id' => 'int',
        'type' => ContactTypeEnum::class,
        'main' => 'bool',
    ];

    public function __toString()
    {
        return (string)$this->value;
    }
}
