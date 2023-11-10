<?php

namespace App\Admin\Models\Client;

use App\Admin\Enums\Contract\StatusEnum;
use App\Admin\Support\Models\HasPeriod;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Module\Client\Invoicing\Application\UseCase\Document\GetDocumentFiles;
use Module\Client\Invoicing\Application\UseCase\Document\UploadDocumentFiles;
use Module\Shared\Dto\FileDto;
use Module\Shared\Dto\UploadedFileDto;
use Sdk\Module\Database\Eloquent\Model;
use Sdk\Module\Support\DateTime;

/**
 * App\Admin\Models\Client\Document
 *
 * @property int $id
 * @property int $client_id
 * @property string $number
 * @property DocumentTypeEnum $type
 * @property StatusEnum $status
 * @property Collection<FileDto>|FileDto[] $files
 * @property DateTime $date_start
 * @property DateTime $date_end
 * @property DateTime $created_at
 * @property DateTime $updated_at
 * @property CarbonPeriod $period
 * @method static Builder|Document newModelQuery()
 * @method static Builder|Document newQuery()
 * @method static Builder|Document query()
 * @method static Builder|Document whereCreatedAt($value)
 * @method static Builder|Document whereUpdatedAt($value)
 * @method static Builder|Document whereDateStart($value)
 * @method static Builder|Document whereDateEnd($value)
 * @method static Builder|Document whereClientId($value)
 * @method static Builder|Document whereId($value)
 * @method static Builder|Document whereStatus($value)
 * @method static Builder|Document active()
 * @mixin \Eloquent
 */
class Document extends Model
{
    use HasPeriod;

    protected $table = 'client_documents';

    protected $fillable = [
        'client_id',
        'type',
        'status',
        'number',
        'date_start',
        'date_end',

        'files'
    ];

    protected $casts = [
        'date_start' => 'date',
        'date_end' => 'date',
        'type' => DocumentTypeEnum::class,
        'status' => StatusEnum::class
    ];

    /** @var UploadedFile[]|Collection<UploadedFile> $savingFiles */
    private array|Collection $savingFiles = [];

    public static function booted()
    {
        static::saved(function (self $model): void {
//            if ($model->isActive() && $model->type === DocumentTypeEnum::CONTRACT) {
//                static::where('id', '!=', $model->id)
//                    ->whereType(DocumentTypeEnum::CONTRACT)
//                    ->whereStatus(StatusEnum::ACTIVE)
//                    ->update(['status' => StatusEnum::INACTIVE]);
//            }

            if (count($model->savingFiles) === 0) {
                return;
            }
            $fileDtos = array_map(fn(UploadedFile $file) => UploadedFileDto::fromUploadedFile($file), $model->savingFiles);
            app(UploadDocumentFiles::class)->execute($model->id, $fileDtos);
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
            get: fn() => app(GetDocumentFiles::class)->execute($this->id),
            set: function ($files) {
                $this->savingFiles = $files ?? [];

                return [];
            }
        );
    }

    public function isActive(): bool
    {
        return $this->status === StatusEnum::ACTIVE;
    }

    public function __toString()
    {
        if ($this->type === DocumentTypeEnum::CONTRACT) {
            return 'Договор №' . $this->number;
        }

        return 'Документ №' . $this->number;
    }
}
