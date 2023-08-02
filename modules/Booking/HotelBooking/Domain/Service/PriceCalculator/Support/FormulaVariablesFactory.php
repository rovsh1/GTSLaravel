<?php

namespace Module\Booking\HotelBooking\Domain\Service\PriceCalculator\Support;

use Module\Booking\Common\Domain\Adapter\ClientAdapterInterface;
use Module\Booking\Order\Domain\Entity\Order;
use Module\Hotel\Application\Response\MarkupSettingsDto;
use Module\Shared\Domain\Adapter\ConstantAdapterInterface;
use Module\Shared\Enum\Client\LegalTypeEnum;

class FormulaVariablesFactory
{
    public function __construct(
        private readonly ClientAdapterInterface $clientAdapter,
        private readonly ConstantAdapterInterface $constantAdapter,
    ) {
    }

    public function fromDataHelper(RoomDataHelper $dataHelper): FormulaVariables
    {
        return new FormulaVariables(
            isResident: $dataHelper->isResident(),
            guestsCount: $dataHelper->guestsCount(),
            vatPercent: $dataHelper->markupDto->vat,
            clientMarkupPercent: $this->calculateClientMarkupPercent($dataHelper),
            touristTax: $this->calculateTouristTax($dataHelper->markupDto->touristTax),
            earlyCheckInPercent: $dataHelper->earlyCheckInPercent(),
            lateCheckOutPercent: $dataHelper->lateCheckOutPercent()
        );
    }

    private function calculateTouristTax(int $taxPercent): float
    {
        return $this->constantAdapter->basicCalculatedValue() * $taxPercent / 100;
    }

    private function calculateClientMarkupPercent(RoomDataHelper $dataHelper): int
    {
        //@todo проверить, что будет если в отеле не заполнены наценки при калькуляции
        //TODO путаница в наименованиях ДТО
        $hotelMarkups = $dataHelper->markupDto->clientMarkups;
        $roomMarkups = $dataHelper->roomMarkupDto;
        $legalId = $dataHelper->order->legalId()?->value();
        if ($legalId) {
            $legal = $this->clientAdapter->findLegal($legalId);
            $legalType = LegalTypeEnum::from($legal->type);

            return $roomMarkups->{$legalType->getKey()} ?? $hotelMarkups->{$legalType->getKey()};
        }

        return $roomMarkups->individual ?? $hotelMarkups->individual;
    }
}
