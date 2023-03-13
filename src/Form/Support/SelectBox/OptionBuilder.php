<?php

namespace Gsdk\Form\Support\SelectBox;

use Gsdk\Form\Support\Radio\ItemBuilder;

class OptionBuilder extends ItemBuilder
{
    private static array $groupKeys = ['parent_id', 'group_id'];

    public function setGroup($key): static
    {
        $this->option->groupId = $this->find($key, 'groupId', self::$groupKeys);

        return $this;
    }
}
