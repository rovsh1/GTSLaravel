<?php

declare(strict_types=1);

namespace Module\Client\Application\Admin\UseCase;

use Module\Client\Application\Admin\Request\SetLegalBankRequisitesDto;
use Module\Client\Domain\Repository\LegalRepositoryInterface;
use Module\Client\Domain\ValueObject\BankRequisites;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;
use Sdk\Module\Foundation\Exception\EntityNotFoundException;

class SetLegalBankRequisites implements UseCaseInterface
{
    public function __construct(
        private readonly LegalRepositoryInterface $repository
    ) {}

    public function execute(SetLegalBankRequisitesDto $request): void
    {
        $legal = $this->repository->get($request->legalId);
        if ($legal === null) {
            throw new EntityNotFoundException('Legal not found');
        }
        $requisites = new BankRequisites(
            bik: $request->bik,
            bankName: $request->bankName,
            inn: $request->inn,
            okpo: $request->okpo,
            correspondentAccount: $request->correspondentAccount,
            kpp: $request->kpp,
            currentAccount: $request->currentAccount,
            cityName: $request->cityName,
        );
        $legal->setRequisites($requisites);
        $this->repository->store($legal);
    }
}
