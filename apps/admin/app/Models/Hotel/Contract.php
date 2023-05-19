<?php

namespace App\Admin\Models\Hotel;

use App\Admin\Enums\Hotel\Contract\StatusEnum;
use App\Admin\Files\ContractDocument;
use App\Admin\Support\Models\HasPeriod;
use Carbon\CarbonPeriod;
use Custom\Framework\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;

/**
 * App\Admin\Models\Hotel\Contract
 *
 * @property int $id
 * @property int $hotel_id
 * @property int $status
 * @property \Custom\Framework\Support\DateTime $date_start
 * @property \Custom\Framework\Support\DateTime $date_end
 * @property \Custom\Framework\Support\DateTime $created_at
 * @property \Custom\Framework\Support\DateTime $updated_at
 * @property CarbonPeriod $period
 * @property Collection<ContractDocument>|ContractDocument[] $documents
 * @property-read Collection<Season>|Season[] $seasons
 * @method static \Illuminate\Database\Eloquent\Builder|Contract newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Contract newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Contract query()
 * @method static \Illuminate\Database\Eloquent\Builder|Contract whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contract whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contract whereDateStart($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contract whereDateEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contract whereHotelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contract whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contract whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contract active()
 * @mixin \Eloquent
 */
class Contract extends Model
{
    use HasPeriod;

    protected $table = 'hotel_contracts';

    protected $fillable = [
        'hotel_id',
        'status',
        'date_start',
        'date_end',
        'documents'
    ];

    protected $casts = [
        'date_start' => 'date',
        'date_end' => 'date',
        'status' => StatusEnum::class
    ];

    /** @var UploadedFile[]|Collection<UploadedFile> $savingFiles */
    private array|Collection $savingFiles = [];

    public static function booted()
    {
        static::saved(function (self $model): void {
            if ($model->isActive()) {
                static::where('id', '!=', $model->id)
                    ->whereStatus(StatusEnum::ACTIVE)
                    ->update(['status' => StatusEnum::INACTIVE]);
            }

            if (count($model->savingFiles) === 0) {
                return;
            }
            foreach ($model->savingFiles as $file) {
                ContractDocument::create(
                    $model->id,
                    $file->getClientOriginalName(),
                    $file->get()
                );
            }
            $model->savingFiles = [];
        });
    }

    public function scopeActive(Builder $builder)
    {
        $builder->whereStatus(StatusEnum::ACTIVE);
    }

    public function documents(): Attribute
    {
        return Attribute::make(
            get: fn() => ContractDocument::getEntityFiles($this->id),
            set: function (array|Collection|null $files) {
                if ($files === null) {
                    return [];
                }
                $this->savingFiles = $files;
                return [];
            }
        );
    }

    public function seasons(): HasMany
    {
        return $this->hasMany(Season::class);
    }

    public function isActive(): bool
    {
        return $this->status === StatusEnum::ACTIVE;
    }

    public function __toString()
    {
        return 'Договор №' . str_pad($this->id, 6, '0', STR_PAD_LEFT);
    }
}
