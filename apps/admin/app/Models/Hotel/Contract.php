<?php

namespace App\Admin\Models\Hotel;

use App\Admin\Enums\Hotel\Contract\StatusEnum;
use App\Admin\Files\ContractDocument;
use App\Admin\Support\Facades\Format;
use Carbon\CarbonPeriod;
use Custom\Framework\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;

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
 * @property CarbonPeriod $period
 * @property Collection<ContractDocument>|ContractDocument[] $documents
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
        'period',
        'documents'
    ];

    protected $casts = [
        'date_start' => 'date',
        'date_end' => 'date',
        'status' => StatusEnum::class
    ];

    public static function booted()
    {
        static::saving(function (self $model) {
            if (!\Arr::has($model, 'number')) {
                //@todo генерация номера договора
                $model['number'] = random_int(123, 9999);
            }
        });
    }

    public function scopeActive(Builder $builder)
    {
        $builder->whereStatus(StatusEnum::ACTIVE);
    }

    public function period(): Attribute
    {
        return Attribute::make(
            get: fn() => new CarbonPeriod($this->date_start, $this->date_end),
            set: fn(CarbonPeriod $period) => [
                'date_start' => $period->getStartDate(),
                'date_end' => $period->getEndDate()
            ]
        );
    }

    public function documents(): Attribute
    {
        return Attribute::get(fn() => ContractDocument::getEntityFiles($this->id));
    }

    public function setDocumentsAttribute(array|Collection|null $files): array
    {
        if ($files === null) {
            return [];
        }
        /** @var UploadedFile[] $files */
        foreach ($files as $file) {
            ContractDocument::create(
                $this->id,
                $file->getClientOriginalName(),
                $file->get()
            );
        }
        return [];
    }

    public function __toString()
    {
        return Format::contractNumber($this->number);
    }
}
