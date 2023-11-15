<?php

namespace App\Admin\Models\Hotel;

use App\Admin\Enums\Contract\StatusEnum;
use App\Admin\Support\Models\HasPeriod;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Module\Hotel\Moderation\Application\UseCase\GetContractDocuments;
use Module\Hotel\Moderation\Application\UseCase\UploadContractDocuments;
use Module\Shared\Dto\FileDto;
use Module\Shared\Dto\UploadedFileDto;
use Sdk\Module\Database\Eloquent\Model;
use Sdk\Module\Support\DateTime;

/**
 * App\Admin\Models\Hotel\Contract
 *
 * @property int $id
 * @property int $hotel_id
 * @property StatusEnum $status
 * @property Collection<FileDto>|FileDto[] $documents
 * @property DateTime $date_start
 * @property DateTime $date_end
 * @property DateTime $created_at
 * @property DateTime $updated_at
 * @property CarbonPeriod $period
 * @property-read Collection<Season>|Season[] $seasons
 * @method static Builder|Contract newModelQuery()
 * @method static Builder|Contract newQuery()
 * @method static Builder|Contract query()
 * @method static Builder|Contract whereCreatedAt($value)
 * @method static Builder|Contract whereUpdatedAt($value)
 * @method static Builder|Contract whereDateStart($value)
 * @method static Builder|Contract whereDateEnd($value)
 * @method static Builder|Contract whereHotelId($value)
 * @method static Builder|Contract whereId($value)
 * @method static Builder|Contract whereStatus($value)
 * @method static Builder|Contract active()
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

        'files',
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
            $fileDtos = array_map(fn(UploadedFile $file) => UploadedFileDto::fromUploadedFile($file),
                $model->savingFiles);
            app(UploadContractDocuments::class)->execute($model->id, $fileDtos);
            $model->savingFiles = [];
        });
    }

    public function scopeActive(Builder $builder)
    {
        $builder->whereStatus(StatusEnum::ACTIVE);
    }

    public function files(): Attribute
    {
        return Attribute::make(
            get: fn() => app(GetContractDocuments::class)->execute($this->id),
            set: function ($files) {
                $this->savingFiles = $files ?? [];

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
