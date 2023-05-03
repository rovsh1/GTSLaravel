<?php

declare(strict_types=1);

namespace App\Admin\Support\Adapters\Hotel;

use App\Core\Support\Adapters\AbstractPortAdapter;

class ResidenceConditionAdapter extends AbstractPortAdapter
{
    protected string $module = 'hotel';

    public function getHotelMarkupSettings(int $hotelId): mixed
    {
        return $this->request('getHotelMarkupSettings', ['hotel_id' => $hotelId]);
    }
}
