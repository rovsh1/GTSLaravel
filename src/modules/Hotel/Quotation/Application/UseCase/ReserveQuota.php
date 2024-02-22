<?php

namespace Module\Hotel\Quotation\Application\UseCase;

use Module\Hotel\Quotation\Application\RequestDto\ReserveRequestDto;
use Module\Hotel\Quotation\Application\Service\QuotaBooker;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class ReserveQuota implements UseCaseInterface
{
    public function __construct(
        private readonly QuotaBooker $quotaUpdater
    ) {
    }

    public function execute(ReserveRequestDto $requestDto): void
    {
        $this->quotaUpdater->reserve($requestDto);
    }
}