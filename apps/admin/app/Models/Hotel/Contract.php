<?php

namespace App\Admin\Models\Hotel;

use App\Admin\Models\Hotel\Contract\StatusEnum;
use App\Admin\Support\Facades\Format;
use Carbon\CarbonPeriod;
use Custom\Framework\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * App\Admin\Models\Hotel\Contract
 *
 * @property int $id
 * @property int $hotel_id
 * @property int $number
 * @property int $status
 * @property \Custom\Framework\Support\DateTime $date_start
 * @property \Custom\Framework\Support\DateTime $date_end
 * @property \Custom\Framework\Support\DateTime $created_at
 * @property \Custom\Framework\Support\DateTime $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Contract newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Contract newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Contract query()
 * @method static \Illuminate\Database\Eloquent\Builder|Contract whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contract whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contract whereDateStart($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contract whereDateEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contract whereHotelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contract whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contract whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contract whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contract active()
 * @mixin \Eloquent
 */
class Contract extends Model
{
    protected $table = 'hotel_contracts';

    protected $fillable = [
        'hotel_id',
        'number',
        'status',
        'date_start',
        'date_end',
    ];

    protected $casts = [
        'date_start' => 'date',
        'date_end' => 'date',
        'status' => StatusEnum::class
    ];

    public function scopeActive(Builder $builder)
    {
        $builder->whereStatus(StatusEnum::ACTIVE);
    }

    public function getPeriod(): CarbonPeriod
    {
        return new CarbonPeriod($this->date_start, $this->date_end);
    }

    public function __toString()
    {
        return 'Договор №' . Format::number($this->number);
    }
}
