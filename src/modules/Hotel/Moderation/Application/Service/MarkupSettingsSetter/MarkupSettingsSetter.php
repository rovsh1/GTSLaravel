<?php

namespace Module\Hotel\Moderation\Application\Service\MarkupSettingsSetter;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Module\Hotel\Moderation\Application\Enums\UpdateMarkupSettingsActionEnum;
use Module\Hotel\Moderation\Domain\Hotel\Entity\MarkupSettings;
use Module\Hotel\Moderation\Domain\Hotel\Repository\MarkupSettingsRepositoryInterface;
use Module\Hotel\Moderation\Domain\Hotel\ValueObject\MarkupSettings\CancelPeriod;
use Module\Hotel\Moderation\Domain\Hotel\ValueObject\MarkupSettings\CancelPeriodCollection;
use Module\Hotel\Moderation\Domain\Hotel\ValueObject\MarkupSettings\Condition;
use Module\Hotel\Moderation\Domain\Hotel\ValueObject\MarkupSettings\DailyMarkupCollection;
use Module\Hotel\Moderation\Domain\Hotel\ValueObject\MarkupSettings\DailyMarkupOption;
use Module\Hotel\Moderation\Domain\Hotel\ValueObject\MarkupSettings\EarlyCheckInCollection;
use Module\Hotel\Moderation\Domain\Hotel\ValueObject\MarkupSettings\LateCheckOutCollection;

class MarkupSettingsSetter extends AbstractUpdater
{
    private array $dtoKeyPatterns = [
        'settings' => '/^(vat|touristTax)$/',
        'earlyCheckIn' => '/^earlyCheckIn\.(\d+)(?:\.(from|to|percent))?$/',
        'earlyCheckIns' => '/^earlyCheckIn$/',
        'lateCheckOut' => '/^lateCheckOut\.(\d+)(?:\.(from|to|percent))?$/',
        'lateCheckOuts' => '/^lateCheckOut$/',
        'cancelPeriod' => '/^cancelPeriods\.(\d+)(?:\.(from|to))?$/',
        'cancelPeriod.noCheckInMarkup' => '/^cancelPeriods\.(\d+)(?:\.(noCheckInMarkup))\.(percent)?$/',
        'cancelPeriods' => '/^cancelPeriods$/',
        'cancelPeriods.dailyMarkup' => '/^cancelPeriods\.(\d+)\.dailyMarkups\.(\d+)(?:\.(percent|cancelPeriodType|daysCount))?$/',
        'cancelPeriods.dailyMarkups' => '/^cancelPeriods\.(\d+)\.dailyMarkups$/',
    ];

    public function __construct(
        private readonly MarkupSettingsRepositoryInterface $repository
    ) {
    }

    public function update(int $hotelId, string $key, mixed $value, UpdateMarkupSettingsActionEnum $action): void
    {
        $settings = $this->repository->get($hotelId);
        [$object, $keyToUpdate] = $this->findPatternParams($settings, $key);

        switch ($action) {
            case UpdateMarkupSettingsActionEnum::UPDATE:
                $this->updateObject($object, $keyToUpdate, $value);
                break;
            case UpdateMarkupSettingsActionEnum::ADD_TO_COLLECTION:
                $this->addToCollection($object, $value);
                break;
            case UpdateMarkupSettingsActionEnum::DELETE_FROM_COLLECTION:
                $object->offsetUnset($value);
                break;
        }

        $this->repository->update($settings);
    }

    private function findPatternParams(MarkupSettings $settings, string $key): array
    {
        foreach ($this->dtoKeyPatterns as $domainKey => $pattern) {
            if (!preg_match($pattern, $key, $keyParts)) {
                continue;
            }

            $keyToUpdate = Arr::last($keyParts);
            $object = $this->objectFromKey($settings, $domainKey, $keyParts);

            return [$object, $keyToUpdate];
        }

        throw new \InvalidArgumentException("Undefined pattern for key [$key]");
    }

    private function objectFromKey(MarkupSettings $settings, string $domainKey, array $keyParts): mixed
    {
        return match ($domainKey) {
            'settings' => $settings,
            'earlyCheckIn' => $settings->earlyCheckIn()->get($keyParts[1]),
            'earlyCheckIns' => $settings->earlyCheckIn(),
            'lateCheckOut' => $settings->lateCheckOut()->get($keyParts[1]),
            'lateCheckOuts' => $settings->lateCheckOut(),
            'cancelPeriod' => $settings->cancelPeriods()->get($keyParts[1]),
            'cancelPeriod.noCheckInMarkup' => $settings->cancelPeriods()->get($keyParts[1])->noCheckInMarkup(),
            'cancelPeriods' => $settings->cancelPeriods(),
            'cancelPeriods.dailyMarkup' => $settings->cancelPeriods()->get($keyParts[1])->dailyMarkups()->get($keyParts[2]),
            'cancelPeriods.dailyMarkups' => $settings->cancelPeriods()->get($keyParts[1])->dailyMarkups(),
        };
    }

    private function updateObject($object, string $keyToUpdate, mixed $value): void
    {
        if ($object instanceof Condition) {
            (new ConditionUpdater())->update($object, $keyToUpdate, $value);
        } elseif ($object instanceof CancelPeriod) {
            (new CancelPeriodUpdater())->update($object, $keyToUpdate, $value);
        } elseif ($object instanceof DailyMarkupOption) {
            (new DailyMarkupOptionUpdater())->update($object, $keyToUpdate, $value);
        } else {
            $this->setByObjectKey($object, $keyToUpdate, $value);
        }
    }

    private function addToCollection(Collection $collection, mixed $value): void
    {
        if ($collection instanceof CancelPeriodCollection) {
            $item = (new CancelPeriodBuilder())->build($value);
        } elseif ($collection instanceof EarlyCheckInCollection) {
            $item = (new ConditionBuilder())->build($value);
        } elseif ($collection instanceof LateCheckOutCollection) {
            $item = (new ConditionBuilder())->build($value);
        } elseif ($collection instanceof DailyMarkupCollection) {
            $item = (new DailyMarkupOptionBuilder())->build($value);
        } else {
            throw new \InvalidArgumentException('Can not add item to collection. Invalid item');
        }

        $collection->add($item);
    }
}
