<?php

namespace Gsdk\Form\Element;

use Gsdk\Form\ElementInterface;
use Gsdk\Form\ElementsParentInterface;
use Gsdk\Form\Form;
use Gsdk\Form\Label;
use Gsdk\Form\Support\HasDefaults;
use Gsdk\Form\Support\Element\RulesBuilder;

abstract class AbstractElement implements ElementInterface
{
    use HasDefaults;

    protected array $options = [];

    protected ?ElementsParentInterface $parent = null;

    protected Label $label;

    protected mixed $value = null;

    protected array $errors = [];

    protected bool $rendered = false;

    public function __construct(private readonly string $name, array $options = [])
    {
        $value = $options['value'] ?? $options['default'] ?? null;
        unset($options['value']);

        if (isset($options['default'])) {
            $options['default'] = $this->prepareValue($options['default']);
        }

        $this->options = Form::mergeElementOptions(static::$defaultOptions, $this->options, $options);

        $this->setValue($value);

        $labelOptions = $this->options['label'] ?? [];
        if (is_string($labelOptions)) {
            $labelOptions = ['text' => $labelOptions];
        }
        $this->label = new Label($labelOptions);
        $this->label->setElement($this);
    }

    public function __get($name)
    {
        return match ($name) {
            'name' => $this->name,
            'value' => $this->getValue(),
            'id' => $this->getInputId(),
            'type' => $this->type(),
            'inputName' => $this->getInputName(),
            default => $this->options[$name] ?? null,
        };
    }

    public function type(): string
    {
        return strtolower((new \ReflectionClass($this))->getShortName());
    }

    public function setParent(ElementsParentInterface $parent): static
    {
        $this->parent = $parent;
        return $this;
    }

    public function setForm($form): static
    {
        return $this->setParent($form);
    }

    public function getForm(): ?Form
    {
        if (null === $this->parent) {
            return null;
        } elseif ($this->parent instanceof Form) {
            return $this->parent;
        } else {
            return $this->parent->getForm();
        }
    }

    public function getInputId(): ?string
    {
        if (isset($this->options['id'])) {
            return $this->options['id'];
        } elseif (!$this->parent) {
            return null;
        }

        return $this->options['id'] = ($this->parent->id . '_' . $this->name);
    }

    public function getInputName(): string
    {
        if (isset($this->options['inputName'])) {
            return $this->options['inputName'];
        }

        if ($this->parent instanceof ElementInterface) {
            $name = $this->parent->getInputName();
        } else {
            $name = $this->parent->name;
        }

        if ($name) {
            $name .= '[' . $this->name . ']';
        } else {
            $name = $this->name;
        }

        return $this->options['inputName'] = $name;
    }

    public function getLabel(): Label
    {
        return $this->label;
    }

    public function checkValue($value): bool
    {
        return true;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function setValue($value)
    {
        $this->value = $this->validateValue($value);
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function setErrors(string|array|null $error): static
    {
        if (is_null($error)) {
            $this->errors = [];
        } elseif (is_array($error)) {
            $this->errors = $error;
        } else {
            $this->errors = [$error];
        }
        return $this;
    }

    public function hasError(): bool
    {
        return !empty($this->errors);
    }

    public function isHidden(): bool
    {
        return false;
    }

    public function isEmpty(): bool
    {
        return empty($this->getValue());
    }

    public function isDisabled(): bool
    {
        return (bool)$this->disabled;
    }

    public function isRequired(): bool
    {
        return (bool)$this->required;
    }

    public function isRenderable(): bool
    {
        return false !== $this->render;
    }

    public function isValid(): bool
    {
        return !$this->isDisabled() && ($this->error || ($this->isRequired() && $this->isEmpty()));
    }

    public function isSubmittable(): bool
    {
        return true;
    }

    public function isFileUpload(): bool
    {
        return false;
    }

    public function reset(): static
    {
        $this->value = null;
        $this->error = null;
        $this->rendered = false;
        return $this;
    }

    public function render(): string
    {
        $this->rendered = true;

        return $this->getHtml();
    }

    public function setRendered(bool $flag): static
    {
        $this->rendered = $flag;

        return $this;
    }

    public function isRendered(): bool
    {
        return $this->rendered;
    }

    public function rules(): array|string
    {
        if ($this->rules) {
            return $this->rules;
        } else {
            return (new RulesBuilder())->build($this);
        }
    }

    public function __toString(): string
    {
        return $this->render();
    }

    protected function validateValue($value)
    {
        return $this->prepareValue($value);
    }

    protected function prepareValue($value)
    {
        return $this->castValue($value, $this->options['cast'] ?? 'default');
    }

    protected function castValue($value, string $cast)
    {
        if (is_null($value)) {
            return null;
        }

        return match ($cast) {
            'int', 'integer' => (int)$value,
            'string' => (string)$value,
            'bool', 'boolean' => (bool)$value,
            'real', 'float', 'double' => $this->fromFloat($value),
            'decimal' => number_format($value, explode(':', $cast, 2)[1], '.', ''),
            default => $value,
        };
    }

    protected function fromFloat($value): float
    {
        return match ((string)$value) {
            'Infinity' => INF,
            '-Infinity' => -INF,
            'NaN' => NAN,
            default => (float)$value,
        };
    }

    protected static function escape($val)
    {
        if (is_array($val)) {
            $val = implode(',', $val);
        } else {
            if (is_float($val)) {
                return str_replace(',', '.', $val);
            }
        }

        return str_replace('"', '&quot;', $val);
    }
}
