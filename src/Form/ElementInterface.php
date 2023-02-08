<?php

namespace Gsdk\Form;

interface ElementInterface
{
    public function render(): string;

    public function getInputName(): string;

    public function isHidden(): bool;

    public function isEmpty(): bool;

    public function isValid(): bool;

    public function isRenderable(): bool;

    public function getHtml(): string;
}
