<?php

declare(strict_types=1);

namespace App\Admin\Models\Pricing;

use Sdk\Module\Database\Eloquent\HasQuicksearch;
use Sdk\Module\Database\Eloquent\Model;
use Sdk\Shared\Enum\Pricing\ValueTypeEnum;

class MarkupGroup extends Model
{
    use HasQuicksearch;

    protected $table = 'client_markup_groups';

    protected array $quicksearch = ['id', 'client_markup_groups.%name%'];

    protected $fillable = [
        'id',
        'name',
        'value',
        'type'
    ];

    protected $casts = [
        'type' => ValueTypeEnum::class
    ];

    public function __toString()
    {
        return (string)$this->name;
    }
}
