<?php

namespace App\Admin\Models\Supplier;

use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Builder;
use Sdk\Module\Database\Eloquent\HasQuicksearch;
use Sdk\Module\Database\Eloquent\Model;

class Requisite extends Model
{
    use HasQuicksearch;

    protected array $quicksearch = ['id', 'inn%', 'director_full_name%'];

    protected $table = 'supplier_requisites';

    protected $fillable = [
        'supplier_id',
        'inn',
        'director_full_name',
    ];
}
