<?php

namespace Module\Support\MailManager\Domain\ValueObject;

class MailBody
{
    private string $body;

    public function __construct(string $body)
    {
        $this->body = $this->validateBody($body);
    }

    public function value(): string
    {
        return $this->body;
    }

    public function __toString(): string
    {
        return $this->body;
    }

    private function validateBody(string $body): string
    {
        if (empty($body)) {
            throw new \Exception('Mail body empty');
        }
        return $body;
    }
}