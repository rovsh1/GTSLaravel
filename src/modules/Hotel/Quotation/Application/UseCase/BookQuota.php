<?php

namespace Module\Hotel\Quotation\Application\UseCase;

use Module\Hotel\Quotation\Application\RequestDto\BookRequestDto;
use Module\Hotel\Quotation\Application\Service\QuotaBooker;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class BookQuota implements UseCaseInterface
{
    public function __construct(
        private readonly QuotaBooker $quotaUpdater
    ) {
    }

    public function execute(BookRequestDto $requestDto): void
    {
        $this->quotaUpdater->book($requestDto);
    }
}