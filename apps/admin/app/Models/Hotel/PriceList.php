<?php

namespace App\Admin\Models\Hotel;

use Custom\Framework\Database\Eloquent\HasQuicksearch;
use Custom\Framework\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class PriceList extends Model
{
    use HasQuicksearch;

    public $timestamps = false;

    protected array $quicksearch = ['id', 'client_name%'];

    protected $table = 'price_lists';

    protected $fillable = [
        'client_id',
        'currency_id',
        'date_from',
        'date_to',
        'rate',
        'file_id',
        'status',
    ];

    protected $casts = [
        'date_from' => 'date',
        'date_to' => 'date',
    ];

    public static function booted()
    {
        static::addGlobalScope('default', function (Builder $builder) {
            $builder
                ->addSelect('price_lists.*')
                ->join('clients', 'clients.id', '=', 'price_lists.client_id')
                ->join('r_currencies', 'r_currencies.id', '=', 'price_lists.currency_id')
                ->addSelect('clients.name as client_name')
                ->addSelect('r_currencies.code_char as currency_code_char')
                ->joinTranslatable('r_currencies', 'name as currency_name');
        });
    }

    public function hotels(): BelongsToMany
    {
        return $this->belongsToMany(
            Hotel::class,
            'price_lists_options',
            'price_list_id',
            'entity_id',
            'id',
            'id',
        )->wherePivot('entity','hotel');
    }
}
