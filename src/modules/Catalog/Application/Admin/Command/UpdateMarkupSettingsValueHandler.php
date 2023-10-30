<?php

namespace Module\Catalog\Application\Admin\Command;

use Carbon\CarbonImmutable;
use Carbon\CarbonPeriodImmutable;
use Illuminate\Support\Collection;
use Module\Catalog\Application\Admin\Enums\UpdateMarkupSettingsActionEnum;
use Module\Catalog\Domain\Hotel\Repository\MarkupSettingsRepositoryInterface;
use Module\Catalog\Domain\Hotel\ValueObject\MarkupSettings\CancelMarkupOption;
use Module\Catalog\Domain\Hotel\ValueObject\MarkupSettings\CancelPeriod;
use Module\Catalog\Domain\Hotel\ValueObject\MarkupSettings\CancelPeriodCollection;
use Module\Catalog\Domain\Hotel\ValueObject\MarkupSettings\CancelPeriodTypeEnum;
use Module\Catalog\Domain\Hotel\ValueObject\MarkupSettings\Condition;
use Module\Catalog\Domain\Hotel\ValueObject\MarkupSettings\DailyMarkupCollection;
use Module\Catalog\Domain\Hotel\ValueObject\MarkupSettings\DailyMarkupOption;
use Module\Catalog\Domain\Hotel\ValueObject\MarkupSettings\EarlyCheckInCollection;
use Module\Catalog\Domain\Hotel\ValueObject\MarkupSettings\LateCheckOutCollection;
use Module\Shared\Contracts\Domain\EntityInterface;
use Module\Shared\Contracts\Domain\ValueObjectInterface;
use Module\Shared\ValueObject\Percent;
use Module\Shared\ValueObject\TimePeriod;
use Sdk\Module\Contracts\Bus\CommandHandlerInterface;
use Sdk\Module\Contracts\Bus\CommandInterface;

class UpdateMarkupSettingsValueHandler implements CommandHandlerInterface
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
    ) {}

    public function handle(CommandInterface|UpdateMarkupSettingsValue $command): void
    {
        $settings = $this->repository->get($command->id);
        $object = null;
        $keyToUpdate = null;
        foreach ($this->dtoKeyPatterns as $domainKey => $pattern) {
            if (!preg_match($pattern, $command->key, $keyParts)) {
                continue;
            }
            $keyToUpdate = \Arr::last($keyParts);
            $object = match ($domainKey) {
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
            break;
        }

        if ($command->action === UpdateMarkupSettingsActionEnum::UPDATE) {
            if ($object instanceof Condition) {
                $this->handleUpdateCondition($object, $keyToUpdate, $command->value);
            } elseif ($object instanceof CancelPeriod) {
                $this->handleUpdateCancelPeriod($object, $keyToUpdate, $command->value);
            } elseif ($object instanceof DailyMarkupOption) {
                $this->handleUpdateDailyMarkupOption($object, $keyToUpdate, $command->value);
            } else {
                $this->setByObjectKey($object, $keyToUpdate, $command->value);
            }
        } elseif ($command->action === UpdateMarkupSettingsActionEnum::ADD_TO_COLLECTION) {
            $this->handleAddToCollection($object, $command->value);
        } elseif ($command->action === UpdateMarkupSettingsActionEnum::DELETE_FROM_COLLECTION) {
            $object->offsetUnset($command->value);
        }

        $this->repository->update($settings);
    }

    private function handleUpdateCondition(Condition $condition, string $key, mixed $value): void
    {
        if (is_array($value) && is_numeric($key)) {
            $periodFrom = $condition->timePeriod()->from();
            $periodTo = $condition->timePeriod()->to();
            if (array_key_exists('from', $value)) {
                $periodFrom = $value['from'];
            }
            if (array_key_exists('to', $value)) {
                $periodTo = $value['to'];
            }
            $condition->setTimePeriod(new TimePeriod($periodFrom, $periodTo));

            if (array_key_exists('percent', $value)) {
                $condition->setPriceMarkup(new Percent($value['percent']));
            }

            return;
        }

        if ($key === 'from') {
            $condition->setTimePeriod(
                new TimePeriod($value, $condition->timePeriod()->to())
            );
        } elseif ($key === 'to') {
            $condition->setTimePeriod(
                new TimePeriod($condition->timePeriod()->from(), $value)
            );
        } elseif ($key === 'percent') {
            $condition->setPriceMarkup(new Percent($value));
        }
    }

    private function handleUpdateCancelPeriod(CancelPeriod $cancelPeriod, string $key, mixed $value): void
    {
        if (is_numeric($key) && is_array($value)) {
            $newPeriod = $this->buildCancelPeriod($value);
            $cancelPeriod->setPeriod($newPeriod->period());
            $cancelPeriod->setNoCheckInMarkup($newPeriod->noCheckInMarkup());

            return;
        }

        $startDate = $cancelPeriod->period()->getStartDate();
        $endDate = $cancelPeriod->period()->getEndDate();
        if ($key === 'from') {
            $startDate = new CarbonImmutable($value);
            $period = new CarbonPeriodImmutable($startDate, $endDate, $cancelPeriod->period()->getDateInterval());
            $cancelPeriod->setPeriod($period);
        } elseif ($key === 'to') {
            $endDate = new CarbonImmutable($value);
            $period = new CarbonPeriodImmutable($startDate, $endDate, $cancelPeriod->period()->getDateInterval());
            $cancelPeriod->setPeriod($period);
        } else {
            $this->setByObjectKey($cancelPeriod, $key, $value);
        }
    }

    private function handleUpdateDailyMarkupOption(DailyMarkupOption $dailyMarkup, string $key, mixed $value): void
    {
        if (is_numeric($key) && is_array($value)) {
            $newDailyMarkup = $this->buildDailyMarkupOption($value);
            $dailyMarkup->setPercent($newDailyMarkup->percent());
            $dailyMarkup->setCancelPeriodType($newDailyMarkup->cancelPeriodType());
            $dailyMarkup->setDaysCount($newDailyMarkup->daysCount());

            return;
        }
        $this->setByObjectKey($dailyMarkup, $key, $value);
    }

    private function handleAddToCollection(Collection $collection, mixed $value): void
    {
        $item = null;
        if ($collection instanceof CancelPeriodCollection) {
            $item = $this->buildCancelPeriod($value);
        } elseif ($collection instanceof EarlyCheckInCollection) {
            $item = $this->buildCondition($value);
        } elseif ($collection instanceof LateCheckOutCollection) {
            $item = $this->buildCondition($value);
        } elseif ($collection instanceof DailyMarkupCollection) {
            $item = $this->buildDailyMarkupOption($value);
        }

        if ($item === null) {
            throw new \InvalidArgumentException('Can not add item to collection. Invalid item');
        }
        $collection->add($item);
    }

    private function buildCondition(array $data): Condition
    {
        if (!\Arr::has($data, ['from', 'to', 'percent'])) {
            throw new \InvalidArgumentException('Can not add condition: Invalid item');
        }

        return new Condition(
            timePeriod: new TimePeriod($data['from'], $data['to']),
            priceMarkup: new Percent($data['percent'])
        );
    }

    private function buildCancelPeriod(array $data): CancelPeriod
    {
        if (!\Arr::has($data, ['from', 'to', 'noCheckInMarkup', 'dailyMarkups'])) {
            throw new \InvalidArgumentException('Can not add cancel period: Invalid item');
        }
        if (!\Arr::has($data['noCheckInMarkup'], ['percent', 'cancelPeriodType'])) {
            throw new \InvalidArgumentException('Can not add condition: Invalid noCheckInMarkup item');
        }

        return new CancelPeriod(
            period: new CarbonPeriodImmutable($data['from'], $data['to']),
            noCheckInMarkup: new CancelMarkupOption(
                new Percent($data['noCheckInMarkup']['percent']),
                CancelPeriodTypeEnum::from($data['noCheckInMarkup']['cancelPeriodType'])
            ),
            dailyMarkups: new DailyMarkupCollection(
                array_map(fn(array $item) => $this->buildDailyMarkupOption($item), $data['dailyMarkups'])
            )
        );
    }

    private function buildDailyMarkupOption(array $data): DailyMarkupOption
    {
        if (!\Arr::has($data, ['percent', 'cancelPeriodType', 'daysCount'])) {
            throw new \InvalidArgumentException('Can not add daily markup option: Invalid item');
        }

        return new DailyMarkupOption(
            percent: new Percent($data['percent']),
            cancelPeriodType: CancelPeriodTypeEnum::from($data['cancelPeriodType']),
            daysCount: $data['daysCount']
        );
    }

    private function setByObjectKey(ValueObjectInterface|EntityInterface $object, string $key, mixed $value): void
    {
        $setterMethod = 'set' . \Str::ucfirst($key);
        if (method_exists($object, $setterMethod)) {
            $preparedValue = $value;
            $argumentType = (new \ReflectionClass($object))->getMethod($setterMethod)->getParameters()[0]?->getType(
            )?->getName();
            if (class_exists($argumentType)) {
                $preparedValue = new $argumentType($value);
            }
            $object->$setterMethod($preparedValue);
        } else {
            throw new \InvalidArgumentException("Can not update value for key [$key]");
        }
    }
}
