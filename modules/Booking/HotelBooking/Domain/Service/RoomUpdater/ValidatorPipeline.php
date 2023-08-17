<?php

namespace Module\Booking\HotelBooking\Domain\Service\RoomUpdater;

use Sdk\Module\Contracts\ModuleInterface;

class ValidatorPipeline
{
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

    private function callValidator(string $class, UpdateDataHelper $dataHelper): void
    {
        $validator = $this->module->make($class);
        $validator->validate($dataHelper);
    }
}
