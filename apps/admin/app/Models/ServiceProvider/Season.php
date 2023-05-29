<?php

namespace App\Admin\Models\ServiceProvider;

use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Builder;
use Sdk\Module\Database\Eloquent\HasQuicksearch;
use Sdk\Module\Database\Eloquent\Model;

class Season extends Model
{
    use HasQuicksearch;

    protected array $quicksearch = ['id', 'number%'];

    protected $table = 'service_provider_seasons';

    protected $fillable = [
        'provider_id',
        'number',
        'date_start',
        'date_end',
        'status',

        'period'
    ];

    protected $casts = [
        'provider_id' => 'int',
        'date_start' => 'date',
        'date_end' => 'date',
        'status' => 'bool',
    ];

    public static function booted()
    {
        static::addGlobalScope('default', function (Builder $builder) {
            $builder->orderBy('date_end', 'desc');
        });
    }

    public function setPeriodAttribute(CarbonPeriod $period): void
    {
        $this->date_start = $period->getStartDate();
        $this->date_end = $period->getEndDate();
    }

    public function getPeriodAttribute(): CarbonPeriod
    {
        return new CarbonPeriod($this->date_start, $this->date_end);
    }

    public function getForeignKey()
    {
        return 'season_id';
    }

    public function __toString()
    {
        return (string)$this->number;
    }
}
