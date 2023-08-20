<?php

namespace Module\Shared\Infrastructure\Service\ApplicationContext\Concerns;

trait TagContextTrait
{
    public function addTag(string $tag): void
    {
        if (!isset($this->data['tags'])) {
            $this->data['tags'] = [];
        }
        $this->data['tags'][] = $tag;
    }
}