<?php

namespace Gsdk\Form;

interface ElementInterface
{
    public function type(): string;

    public function setParent(ElementsParentInterface $parent): static;

    public function setForm(Form $form): static;

    public function getForm(): ?Form;

    public function getInputId(): ?string;

    public function getInputName(): string;

    public function getLabel(): Label;

    public function getValue();

    public function setValue($value);

    public function getErrors(): array;

    public function setErrors(string|array|null $error): static;

    public function hasError(): bool;

    public function isHidden(): bool;

    public function isEmpty(): bool;

    public function isDisabled(): bool;

    public function isRequired(): bool;

    public function isRenderable(): bool;

    public function isValid(): bool;

    public function isSubmittable(): bool;

    public function isFileUpload(): bool;

    public function reset(): static;

    public function render(): string;

    public function getHtml(): string;

    public function isRendered(): bool;

    public function rules(): array|string;

    public function __toString(): string;
}
