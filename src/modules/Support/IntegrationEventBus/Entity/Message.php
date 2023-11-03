<?php

namespace Module\Support\IntegrationEventBus\Entity;

use Module\Support\IntegrationEventBus\Enums\MessageStatusEnum;

class Message
{
    public function __construct(
        private readonly string $id,
        private MessageStatusEnum $status,
        private readonly string $module,
        private readonly string $event,
        private readonly array $eventPayload
    ) {
    }

    public function uuid(): string
    {
        return $this->id;
    }

    public function module(): string
    {
        return $this->module;
    }

    public function event(): string
    {
        return $this->event;
    }

    public function eventPayload(): array
    {
        return $this->eventPayload;
    }

//    public function fail

    public function isFailed(): bool
    {
        return $this->status === MessageStatusEnum::FAILED;
    }

    public function isDeleted(): bool
    {
        return $this->status !== MessageStatusEnum::WAITING;
    }

    public function isReleased(): bool
    {
        return $this->status === MessageStatusEnum::WAITING;
    }

    public function serialize(): array
    {
        return [
            'id' => $this->id,
            'status' => $this->status->value,
            'module' => $this->module,
            'event' => $this->event,
            'eventPayload' => $this->eventPayload
        ];
    }

    public static function deserialize(array $payload): Message
    {
        return new Message(
            id: $payload['id'],
            status: MessageStatusEnum::from($payload['status']),
            module: $payload['module'],
            event: $payload['event'],
            eventPayload: $payload['eventPayload'],
        );
    }
}