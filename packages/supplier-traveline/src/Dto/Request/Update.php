<?php

namespace Pkg\Supplier\Traveline\Dto\Request;

use Carbon\Carbon;
use Carbon\CarbonInterface;
use Carbon\CarbonPeriod;

class Update
{
    public function __construct(
        public readonly CarbonInterface $startDateYmd,
        public readonly CarbonInterface $endDateYmd,
        public readonly int $roomTypeId,
        public readonly ?int $ratePlanId,
        //todo тут скорее всего модель или енум: AMD, AZN, BGN, BYN, CAD, CHF, CNY, EUR, GBR, INR, KGS, KRW, KZT, MDL, NOK, PLN, RUB, TJS, UAN, USD, UZS.
        public readonly ?string $currencyCode,
        public readonly ?bool $closed,
        /** @var Price[]|null $prices */
        public readonly ?array $prices,
        public readonly ?int $quota,
        public readonly ?int $releaseDays,
    ) {}

    public function getDatePeriod(): CarbonPeriod
    {
        return new CarbonPeriod($this->startDateYmd, $this->endDateYmd);
    }

    public function hasQuota(): bool
    {
        return $this->quota !== null;
    }

    public function hasReleaseDays(): bool
    {
        return $this->releaseDays !== null;
    }

    public function hasPrices(): bool
    {
        return $this->prices !== null;
    }

    public function isClosed(): bool
    {
        return $this->closed !== null && $this->closed;
    }

    public function isOpened(): bool
    {
        return $this->closed !== null && !$this->closed;
    }

    public static function fromArray(array $data): self
    {
        $prices = array_key_exists('prices', $data)
            ? Price::collectionFromArray($data['prices'])
            : null;

        $isClosed = $data['closed'] ?? null;
        if ($isClosed !== null && is_string($isClosed)) {
            $isClosed = $isClosed === 'true';
        }

        return new self(
            new Carbon($data['startDateYmd']),
            new Carbon($data['endDateYmd']),
            $data['roomTypeId'],
            $data['ratePlanId'] ?? null,
            $data['currencyCode'] ?? null,
            $isClosed,
            $prices,
            $data['quota'] ?? null,
            $data['minimumAdvanceBooking'] ?? null,
        );
    }

    /**
     * @param array $items
     * @return self[]
     */
    public static function collectionFromArray(array $items): array
    {
        return array_map(fn(array $data) => static::fromArray($data), $items);
    }
}