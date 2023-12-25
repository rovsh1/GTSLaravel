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
        private readonly string $payload,
        private readonly array $context
    ) {}

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

    public function payload(): string
    {
        return $this->payload;
    }

    public function context(): array
    {
        return $this->context;
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
            'payload' => $this->payload,
            'context' => $this->context,
        ];
    }

    public static function deserialize(array $payload): Message
    {
        return new Message(
            id: $payload['id'],
            status: MessageStatusEnum::from($payload['status']),
            module: $payload['module'],
            event: $payload['event'],
            payload: $payload['payload'],
            context: $payload['context'],
        );
    }
}
