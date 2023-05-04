<?php

declare(strict_types=1);

namespace Module\Hotel\Domain\Service;

use Module\Hotel\Domain\Repository\MarkupSettingsRepositoryInterface;

class MarkupSettingsUpdater
{
    public function __construct(
        private readonly MarkupSettingsRepositoryInterface $repository,
    ) {}

    /**
     * @param int $hotelId
     * @param int|null $individual
     * @param int|null $OTA
     * @param int|null $TA
     * @param int|null $TO
     * @return bool
     * @throws \InvalidArgumentException
     */
    public function updateClientMarkups(int $hotelId, ?int $individual, ?int $OTA, ?int $TA, ?int $TO): bool
    {
        $markupSettings = $this->repository->get($hotelId);
        if ($individual !== null) {
            $markupSettings->clientMarkups()->individual()->setValue($individual);
        }
        if ($OTA !== null) {
            $markupSettings->clientMarkups()->OTA()->setValue($OTA);
        }
        if ($TA !== null) {
            $markupSettings->clientMarkups()->TA()->setValue($TA);
        }
        if ($TO !== null) {
            $markupSettings->clientMarkups()->TO()->setValue($TO);
        }
        return $this->repository->updateClientMarkups($hotelId, $markupSettings);
    }
}
