<?php

namespace Module\Hotel\Moderation\Application\Service\MarkupSettingsSetter;

use Module\Hotel\Moderation\Domain\Hotel\ValueObject\MarkupSettings\Condition;
use Sdk\Shared\ValueObject\Percent;
use Sdk\Shared\ValueObject\TimePeriod;

class ConditionUpdater
{
    public function update(Condition $condition, string $key, mixed $value): void
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
}