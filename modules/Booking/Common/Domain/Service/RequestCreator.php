<?php

declare(strict_types=1);

namespace Module\Booking\Common\Domain\Service;

use Module\Booking\Common\Domain\Entity\Booking;
use Module\Booking\Common\Domain\Entity\Request;
use Module\Booking\Common\Domain\Factory\DocumentGeneratorFactory;

class RequestCreator
{
    public function __construct(
        private readonly DocumentGeneratorFactory $documentGeneratorFactory,
        //@todo репозиторий
    ) {}

    public function create(Booking $booking): Request
    {
        //@todo сохранить файл
        $file = $this->generateFile($booking);
        //@todo создать и сохранить доменную модель Request
        return new Request(1, '123asd');
    }

    private function generateFile(Booking $booking)
    {
        $documentGenerator = $this->documentGeneratorFactory->getDocumentGenerator($booking)->generate($booking);
    }
}
