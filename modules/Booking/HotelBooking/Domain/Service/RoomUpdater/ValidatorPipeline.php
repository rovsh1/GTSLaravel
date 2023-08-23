<?php

namespace Module\Booking\HotelBooking\Domain\Service\RoomUpdater;

use Module\Booking\HotelBooking\Domain\Service\RoomUpdater\Validator\ValidatorInterface;
use Sdk\Module\Contracts\ModuleInterface;

class ValidatorPipeline
{
    /** @var class-string<ValidatorInterface>[] $validators */
    private array $validators = [];

    public function __construct(
        private readonly ModuleInterface $module
    ) {}

    public function send(UpdateDataHelper $dataHelper): static
    {
        foreach ($this->validators as $validator) {
            $this->callValidator($validator, $dataHelper);
        }

        return $this;
    }

    /**
     * @param class-string<ValidatorInterface> $validator
     * @return $this
     */
    public function through(string $validator): static
    {
        $this->validators[] = $validator;

        return $this;
    }

    /**
     * @param bool $needValidate
     * @param class-string<ValidatorInterface> $validator
     * @return $this
     */
    public function throughWhen(bool $needValidate, string $validator): static
    {
        if ($needValidate) {
            $this->validators[] = $validator;
        }

        return $this;
    }

    /**
     * @param class-string<ValidatorInterface> $class
     * @param UpdateDataHelper $dataHelper
     * @return void
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    private function callValidator(string $class, UpdateDataHelper $dataHelper): void
    {
        /** @var ValidatorInterface $validator */
        $validator = $this->module->make($class);
        $validator->validate($dataHelper);
    }
}
