<?php

namespace App\Admin\Support\Context;

use App\Admin\Support\View\Form\Form;
use Sdk\Shared\Contracts\Context\ContextInterface;
use Sdk\Shared\Contracts\Context\HttpContextInterface;
use Sdk\Shared\Enum\SourceEnum;
use Sdk\Shared\Support\ApplicationContext\AbstractContext;
use Sdk\Shared\Support\ApplicationContext\Concerns\AdministratorContextTrait;
use Sdk\Shared\Support\ApplicationContext\Concerns\HttpRequestContextTrait;

class ContextManager extends AbstractContext implements ContextInterface, HttpContextInterface
{
    use AdministratorContextTrait;
    use HttpRequestContextTrait;

    private Form $submittedForm;

    public function __construct()
    {
        $this->generateRequestId();
        $this->setSource(SourceEnum::ADMIN);
    }

    public function setSubmittedForm(Form $form): void
    {
        $this->submittedForm = $form;
    }

    public function submittedForm(): ?Form
    {
        return $this->submittedForm ?? null;
    }

    public function toArray(): array
    {
        $data = parent::toArray();
        if (isset($this->submittedForm)) {
            $data['formData'] = $this->submittedForm->getData();
        }

        return $data;
    }
}
