<?php

namespace App\Hotel\Models;

use App\Admin\Models\HasIndexedChildren;
use App\Hotel\Models\Reference\Usability;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use Sdk\Module\Database\Eloquent\HasTranslations;
use Sdk\Module\Database\Eloquent\Model;
use Sdk\Shared\Dto\FileDto;

/**
 * @property int id
 * @property HasMany beds
 * @property-read string $name
 * @property-read Image $images
 * @property-read FileDto|null $main_image
 * @property-read Collection<int, Usability> $usabilities
 */
class Room extends Model
{
    use HasIndexedChildren;
    use HasTranslations;

    public $timestamps = false;

    protected $table = 'hotel_rooms';

    protected array $translatable = ['name', 'text'];

    protected $fillable = [
        'hotel_id',
        'type_id',
        'rooms_number',
        'guests_count',
        'square',
        'position',
        'name',
        'text',
    ];

    protected $casts = [
        'hotel_id' => 'int',
        'type_id' => 'int',
        'rooms_number' => 'int',
        'guests_count' => 'int',
        'square' => 'int',
        'position' => 'int',
    ];

    public static function getRoomNames(string $lang): array
    {
        return DB::table('hotel_rooms_translation')
            ->where('language', $lang)
            ->select('name')
            ->distinct()
            ->get()
            ->pluck('name')
            ->toArray();
    }

    public static function booted()
    {
        static::addGlobalScope('default', function (Builder $builder) {
            $builder
                ->addSelect('hotel_rooms.*')
                //->join('clients', 'clients.id', '=', 'price_lists.client_id')
                ->join('r_enums as r_types', 'r_types.id', '=', 'hotel_rooms.type_id')
                ->joinTranslatable('r_types', 'name as type_name')
                ->joinTranslations()
                ->orderBy('position', 'asc');
        });
    }

    public function scopeWhereId(Builder $builder, int $id): void
    {
        $builder->where('hotel_rooms.id', $id);
    }

    public function mainImage(): Attribute
    {
        return Attribute::get(fn() => $this->images()->first()?->file);
    }

    public function images(): BelongsToMany
    {
        return $this->belongsToMany(
            Image::class,
            'hotel_room_images',
            'room_id',
            'image_id',
            'id',
            'id'
        )->orderBy('hotel_room_images.index');
    }

    public function usabilities(): BelongsToMany
    {
        return $this->belongsToMany(
            Usability::class,
            'hotel_usabilities',
            'hotel_id',
            'usability_id',
            'hotel_id',
            'id'
        )->where(function (Builder $builder) {
            $builder->where('hotel_usabilities.room_id', $this->id)
                ->orWhereNull('hotel_usabilities.room_id');
        });
    }

    /**
     * @param int[] $ids
     * @return bool
     * @throws \Throwable
     */
    public function updateImageIndexes(array $ids): bool
    {
        $i = 0;
        foreach ($ids as $id) {
            RoomImage::whereRoomId($this->id)
                ->whereImageId($id)
                ->update(['hotel_room_images.index' => $i++]);
        }

        return true;
    }

    public function __toString()
    {
        return $this->name;
    }
}
