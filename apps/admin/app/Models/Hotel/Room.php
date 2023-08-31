<?php

namespace App\Admin\Models\Hotel;

use App\Admin\Files\HotelImage;
use App\Admin\Models\HasIndexedChildren;
use App\Admin\Support\Facades\Hotel\MarkupSettingsAdapter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Sdk\Module\Database\Eloquent\HasTranslations;
use Sdk\Module\Database\Eloquent\Model;

/**
 * @property int id
 * @property HasMany beds
 * @property-read string $name
 * @property-read Image $images
 * @property-read HotelImage|null $main_image
 * @property-read Collection<int, PriceRate> $priceRates
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

    public function beds(): HasMany
    {
        return $this->hasMany(RoomBed::class);
    }

    public function updateBeds(array $beds)
    {
        RoomBed::where('room_id', $this->id)->delete();
        foreach ($beds as $bed) {
            RoomBed::create(array_merge($bed, ['room_id' => $this->id]));
        }
    }

    public function markupSettings(): Attribute
    {
        return Attribute::get(fn() => MarkupSettingsAdapter::getRoomMarkupSettings($this->hotel_id, $this->id));
    }

    public function mainImage(): Attribute
    {
        return Attribute::get(
            fn() => $this->images()->first() !== null
                ? HotelImage::find($this->images()->first()->file_guid)
                : null
        );
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

    public function priceRates(): BelongsToMany
    {
        return $this->belongsToMany(
            PriceRate::class,
            'hotel_price_rate_rooms',
            'room_id',
            'rate_id',
        );
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
