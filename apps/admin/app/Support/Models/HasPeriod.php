<?php

declare(strict_types=1);

namespace App\Admin\Support\Models;

use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Casts\Attribute;

trait HasPeriod
{
    public function period(): Attribute
    {
        return Attribute::make(
            get: function (mixed $value, array $attributes) {
                $dateStart = $attributes[$this->getStartPeriodField()] ?? null;
                $dateEnd = $attributes[$this->getStartEndField()] ?? null;
                if (empty($dateStart) || empty($dateEnd)) {
                    return null;
                }
                return new CarbonPeriod($dateStart, $dateEnd);
            },
            set: fn(CarbonPeriod $period) => [
                $this->getStartPeriodField() => $period->getStartDate(),
                $this->getStartEndField() => $period->getEndDate()
            ]
        );
    }

    public function getFillable(): array
    {
        return array_merge(['period'], parent::getFillable());
    }

    protected function getStartPeriodField(): string
    {
        return 'date_start';
    }

    protected function getStartEndField(): string
    {
        return 'date_end';
    }
}
