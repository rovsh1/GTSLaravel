<?php

namespace Module\Hotel\Application\Command;

use Carbon\CarbonPeriod;
use Custom\Framework\Contracts\Bus\CommandHandlerInterface;
use Custom\Framework\Contracts\Bus\CommandInterface;
use Illuminate\Support\Collection;
use Module\Hotel\Application\Enums\UpdateMarkupSettingsActionEnum;
use Module\Hotel\Domain\Repository\MarkupSettingsRepositoryInterface;
use Module\Hotel\Domain\ValueObject\MarkupSettings\CancelMarkupOption;
use Module\Hotel\Domain\ValueObject\MarkupSettings\CancelPeriod;
use Module\Hotel\Domain\ValueObject\MarkupSettings\CancelPeriodCollection;
use Module\Hotel\Domain\ValueObject\MarkupSettings\CancelPeriodTypeEnum;
use Module\Hotel\Domain\ValueObject\MarkupSettings\Condition;
use Module\Hotel\Domain\ValueObject\MarkupSettings\DailyMarkupCollection;
use Module\Hotel\Domain\ValueObject\MarkupSettings\DailyMarkupOption;
use Module\Hotel\Domain\ValueObject\MarkupSettings\EarlyCheckInCollection;
use Module\Hotel\Domain\ValueObject\MarkupSettings\LateCheckOutCollection;
use Module\Shared\Domain\Entity\EntityInterface;
use Module\Shared\Domain\ValueObject\Percent;
use Module\Shared\Domain\ValueObject\TimePeriod;
use Module\Shared\Domain\ValueObject\ValueObjectInterface;

class UpdateMarkupSettingsValueHandler implements CommandHandlerInterface
{
    private array $dtoKeyPatterns = [
        'settings' => '/^(vat|touristTax)$/',
        'clientMarkups' => '/^clientMarkups\.(TA|OTA|TO|individual)$/',
        'earlyCheckIn' => '/^earlyCheckIn\.(\d+)(?:\.(from|to|percent))?$/',
        'earlyCheckIns' => '/^earlyCheckIn$/',
        'lateCheckOut' => '/^lateCheckOut\.(\d+)(?:\.(from|to|percent))?$/',
        'lateCheckOuts' => '/^lateCheckOut$/',
        'cancelPeriod' => '/^cancelPeriods\.(\d+)(?:\.(from|to|percent|noCheckInMarkup))?$/',
        'cancelPeriods' => '/^cancelPeriods$/',
        'cancelPeriods.dailyMarkup' => '/^cancelPeriods\.(\d+)\.dailyMarkups\.(\d+)\.(percent|cancelPeriodType|daysCount)$/',
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
                'settings' => $settings->$keyToUpdate(),
                'clientMarkups' => $settings->clientMarkups()->$keyToUpdate(),
                'earlyCheckIn' => $settings->earlyCheckIn()->get($keyParts[1]),
                'earlyCheckIns' => $settings->earlyCheckIn(),
                'lateCheckOut' => $settings->lateCheckOut()->get($keyParts[1]),
                'lateCheckOuts' => $settings->lateCheckOut(),
                'cancelPeriod' => $settings->cancelPeriods()->get($keyParts[1]),
                'cancelPeriods' => $settings->cancelPeriods(),
                'cancelPeriods.dailyMarkup' => $settings->cancelPeriods()->get($keyParts[1])->dailyMarkups()->get(
                    $keyParts[2]
                ),
                'cancelPeriods.dailyMarkups' => $settings->cancelPeriods()->get($keyParts[1])->dailyMarkups(),
            };
            break;
        }

        if ($command->action === UpdateMarkupSettingsActionEnum::UPDATE) {
            if ($object instanceof Condition) {
                $this->handleUpdateCondition($object, $keyToUpdate, $command->value);
            } elseif ($object instanceof CancelPeriod) {
                $this->handleUpdateCancelPeriod($object, $keyToUpdate, $command->value);
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
            if (array_key_exists('from', $value)) {
                $condition->timePeriod()->setFrom($value['from']);
            }
            if (array_key_exists('to', $value)) {
                $condition->timePeriod()->setTo($value['to']);
            }
            if (array_key_exists('percent', $value)) {
                $condition->priceMarkup()->setValue($value['percent']);
            }
            return;
        }

        if ($key === 'from') {
            $condition->timePeriod()->setFrom($value);
        } elseif ($key === 'to') {
            $condition->timePeriod()->setTo($value);
        } elseif ($key === 'percent') {
            $condition->priceMarkup()->setValue($value);
        }
    }

    private function handleUpdateCancelPeriod(CancelPeriod $cancelPeriod, string $key, mixed $value): void
    {
        if ($key === 'from') {
            $cancelPeriod->period()->setStartDate($value);
        } elseif ($key === 'to') {
            $cancelPeriod->period()->setEndDate($value);
        } else {
            $this->setByObjectKey($cancelPeriod, $key, $value);
        }
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
            period: new CarbonPeriod($data['from'], $data['to']),
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
            $object->$setterMethod($value);
        } elseif (method_exists($object, 'setValue')) {
            $object->setValue($value);
        } else {
            throw new \InvalidArgumentException("Can not update value for key [$key]");
        }
    }
}
