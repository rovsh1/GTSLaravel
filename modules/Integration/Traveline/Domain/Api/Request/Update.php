<?php

namespace Module\Integration\Traveline\Domain\Api\Request;

use Carbon\Carbon;
use Carbon\CarbonInterface;
use Carbon\CarbonPeriod;
use Module\Integration\Traveline\Domain\Service\HotelRoomCodeGeneratorInterface;

class Update
{
    public function __construct(
        public readonly CarbonInterface $startDateYmd,
        public readonly CarbonInterface $endDateYmd,
        public readonly int             $roomTypeId,
        public readonly ?int            $ratePlanId,
        //todo тут скорее всего модель или енум: AMD, AZN, BGN, BYN, CAD, CHF, CNY, EUR, GBR, INR, KGS, KRW, KZT, MDL, NOK, PLN, RUB, TJS, UAN, USD, UZS.
        public readonly ?string         $currencyCode,
        public readonly ?bool           $closed,
        /** @var Price[]|null $prices */
        public readonly ?array          $prices,
        public readonly ?int            $quota
    ) {}

    public function getDatePeriod(): CarbonPeriod
    {
        return new CarbonPeriod($this->startDateYmd, $this->endDateYmd);
    }

    public function hasQuota(): bool
    {
        return $this->quota !== null;
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

    public static function fromArray(array $data, HotelRoomCodeGeneratorInterface $codeGenerator): self
    {
        $prices = array_key_exists('prices', $data)
            ? Price::collectionFromArray($data['prices'], $codeGenerator)
            : null;

        return new self(
            new Carbon($data['startDateYmd']),
            new Carbon($data['endDateYmd']),
            $data['roomTypeId'],
            $data['ratePlanId'] ?? null,
            $data['currencyCode'] ?? null,
            $data['closed'] ?? null,
            $prices,
            $data['quota'] ?? null,
        );
    }

    /**
     * @param array $items
     * @return self[]
     */
    public static function collectionFromArray(array $items, HotelRoomCodeGeneratorInterface $codeGenerator): array
    {
        return array_map(fn(array $data) => static::fromArray($data, $codeGenerator), $items);
    }
}
