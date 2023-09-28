<?php

declare(strict_types=1);

namespace Module\Supplier\Infrastructure\Supplier\Models;

use Sdk\Module\Database\Eloquent\Model;

class Requisites extends Model
{
    protected $table = 'supplier_requisites';

    protected $fillable = [
        'supplier_id',
        'inn',
        'director_full_name',
    ];
}
