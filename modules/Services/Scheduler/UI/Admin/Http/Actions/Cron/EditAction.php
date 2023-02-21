<?php

namespace Module\Services\Scheduler\UI\Admin\Http\Actions\Cron;

use GTS\Services\Scheduler\UI\Admin\Http\Actions\Cron\Form;
use Module\Services\Scheduler\Infrastructure\Facade\Cron\CrudFacadeInterface;

class EditAction
{
    private $job;

    public function __construct(
        private readonly CrudFacadeInterface $crudFacade
    ) {}

    public function handle(int $id = null)
    {
        if ($id && !$this->findJob($id))
            return abort(404);

        $form = $this->formFactory();

        if ($this->submit($form))
            return redirect(route('cron.index'));

        return app('layout')
            ->ss('cron/index')
            ->title('Крон задания')
            ->view('cron.form', [
                'form' => $form
            ]);
    }

    private function submit($form): bool
    {
        if (!$form->submit())
            return false;

        $data = $form->getData();

        if ($this->job)
            $this->crudFacade->update($this->job->id, $data);
        else
            $this->job = $this->crudFacade->create($data);

        return true;
    }

    private function formFactory()
    {
        return (new Form())
            ->addElement('command', 'select', [
                'label' => 'Комманда',
                'required' => true,
                'emptyItem' => '',
                'items' => self::getCommands()
            ])
            ->addElement('arguments', 'text', ['label' => 'Параметры'])
            ->addElement('time', 'text', ['label' => 'Время'])
            ->addElement('description', 'textarea', ['label' => 'Описание'])
            //->addElement('user', 'text', ['label' => 'Пользователь'])
            ->addElement('enabled', 'checkbox', ['label' => 'Включена']);
    }

    private function findJob(int $id): bool
    {
        $job = $this->crudFacade->find($id);
        if (!$job)
            return false;

        $this->job = $job;

        return true;
    }
}
