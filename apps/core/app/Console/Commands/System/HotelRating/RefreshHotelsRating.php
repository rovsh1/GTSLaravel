<?php

namespace App\Core\Console\Commands\System\HotelRating;

use App\Admin\Models\Hotel\Hotel;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Query\Builder;

class RefreshHotelsRating extends Command
{
    public bool $cronable = true;

    protected $signature = 'hotel:refresh-rating';

    protected $description = 'Обновить рейтинг отелей на основании отзывов.';

    public function handle()
    {
        Hotel::select(['id'])
            ->withoutGlobalScopes()
            ->selectSub(
                function (Builder $query) {
                    $query->from('hotel_reviews')
                        ->selectRaw('AVG(rating)')
                        ->whereColumn('hotel_reviews.hotel_id', 'hotels.id');
                },
                'rating'
            )
            ->chunk(200, function (Collection $hotels) {
                Hotel::upsert($hotels->toArray(), 'id', ['rating']);
            });
    }
}
