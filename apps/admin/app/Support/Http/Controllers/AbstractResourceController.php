<?php

namespace App\Admin\Support\Http\Controllers;

use App\Admin\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;

abstract class AbstractResourceController extends Controller
{
    protected $resource;

    protected $repository;

    public function __construct()
    {
        $this->resource = app('resources')->getResource($this->resource);
        $this->repository = $this->resource->makeRepository();
    }

    public function index()
    {
        $this->breadcrumbs();

        return $this->indexResourceFactory();
    }

    public function show(int $id)
    {
        $model = $this->repository->findOrFail($id);

        $this->breadcrumbs()
            ->add((string)$model);

        return $this->showResourceFactory($model);
    }

    public function create()
    {
        $this->breadcrumbs()
            ->add($this->resource->title('create') ?? 'Новая запись');

        $form = $this->formFactory()
            ->method('post')
            ->action($this->resource->route('store'));

        return app('layout')
            ->title($this->resource->title('create'))
            ->view($this->resource->view('create') ?? $this->resource->view('form') ?? 'default.form', [
                'form' => $form
            ]);
    }

    public function store()
    {
        $form = $this->formFactory()
            ->method('post');

        if (!$form->submit()) {
            return redirect($this->resource->route('create'))
                ->withErrors($form->errors())
                ->withInput();
        }

        $model = $this->repository->create($form->getData());

        return redirect($this->resource->route('index'));
    }

    public function edit(int $id)
    {
        $model = $this->repository->findOrFail($id);

        $title = (string)$model;
        $breadcrumbs = $this->breadcrumbs();
        if (Route::has($this->resource->routeName('show'))) {//method_exists($this, 'show')
            $breadcrumbs->addUrl($this->resource->route('show'), $title);
        } else {
            $breadcrumbs->add($title);
        }
        $breadcrumbs->add($this->resource->title('edit') ?? 'Редактирование');

        $form = $this->formFactory()
            ->method('put')
            ->action($this->resource->route('update', $id))
            ->data($model);

        return app('layout')
            ->title($title)
            ->view($this->resource->view('edit') ?? $this->resource->view('form') ?? 'default.form', [
                'form' => $form
            ]);
    }

    public function update(int $id)
    {
        $model = $this->repository->findOrFail($id);

        $form = $this->formFactory()
            ->method('put');

        if (!$form->submit()) {
            return redirect($this->resource->route('edit', $model))
                ->withErrors($form->errors())
                ->withInput();
        }

        $this->repository->update($model, $form->getData());

        return redirect($this->resource->route('index'));
    }

    public function destroy(int $id)
    {
        $this->repository->delete($id);

        return redirect($this->resource->route('index'));
    }

    protected function showResourceFactory($model) {}

    protected function indexResourceFactory() {}

    protected function formFactory() {}

    protected function breadcrumbs()
    {
        return app('breadcrumbs')
            ->addUrl($this->resource->route('index'), $this->resource->title('index') ?? 'Default index');
    }
}
