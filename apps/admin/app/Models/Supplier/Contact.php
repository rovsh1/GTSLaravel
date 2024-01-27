<?php

namespace App\Admin\Models\Supplier;

use Sdk\Module\Database\Eloquent\Model;
use Sdk\Shared\Enum\ContactTypeEnum;

class Contact extends Model
{
    protected $table = 'supplier_contacts';

    protected $fillable = [
        'supplier_id',
        'type',
        'value',
        'description',
        'is_main'
    ];

    protected $casts = [
        'supplier_id' => 'int',
        'type' => ContactTypeEnum::class,
        'is_main' => 'bool',
    ];

    public function __toString()
    {
        return (string)$this->value;
    }
}
