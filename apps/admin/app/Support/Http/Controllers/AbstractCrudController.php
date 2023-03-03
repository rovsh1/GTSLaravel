<?php

namespace App\Admin\Support\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use App\Admin\Http\Controllers\Controller;

abstract class AbstractCrudController extends Controller
{
    protected string $model;

    protected string $routePrefix;

    protected array $titles = [];

    protected array $views = [];

    public function __construct()
    {
        $routeName = request()->route()->getName();
        $this->routePrefix = substr($routeName, 0, strrpos($routeName, '.'));
    }

    public function index()
    {
        $this->breadcrumbs();

        return $this->indexResourceFactory();
    }

    public function create()
    {
        $this->breadcrumbs()
            ->add($this->titles['create']);

        $form = $this->formFactory()
            ->method('post')
            ->action($this->route('store'));

        return app('layout')
            ->title($this->titles['create'])
            ->view($this->views['create'] ?? $this->views['form'] ?? 'default.form', [
                'form' => $form
            ]);
    }

    public function store()
    {
        $form = $this->formFactory()
            ->method('post');

        if (!$form->submit()) {
            return redirect($this->route('create'))
                ->withErrors($form->errors())
                ->withInput();
        }

        $model = $this->model::create($form->getData());

        return redirect($this->route('index'));
    }

    public function edit(int $id)
    {
        $model = $this->findModelOrFail($id);

        $title = (string)$model;
        $breadcrumbs = $this->breadcrumbs();
        if (method_exists($this, 'show')) {
            $breadcrumbs->addUrl($this->route('show'), $title);
        } else {
            $breadcrumbs->add($title);
        }
        $breadcrumbs->add($this->title['edit'] ?? 'Редактирование');

        $form = $this->formFactory()
            ->method('put')
            ->action($this->route('update', $id))
            ->data($model);

        return app('layout')
            ->title($title)
            ->view($this->views['edit'] ?? $this->views['form'] ?? 'default.form', [
                'form' => $form
            ]);
    }

    public function update(int $id)
    {
        $model = $this->findModelOrFail($id);

        $form = $this->formFactory()
            ->method('put');

        if (!$form->submit()) {
            return redirect($this->route('edit', $model))
                ->withErrors($form->errors())
                ->withInput();
        }

        $model->update($form->getData());

        return redirect($this->route('index'));
    }

    public function destroy(int $id)
    {
        $model = $this->findModelOrFail($id);

        $model->delete();

        return redirect($this->route('index'));
    }

    abstract protected function indexResourceFactory();

    abstract protected function formFactory();

    protected function breadcrumbs()
    {
        return app('breadcrumbs')
            ->addUrl($this->route('index'), $this->titles['index']);
    }

    protected function route(string $method, $params = []): string
    {
        return route($this->routePrefix . '.' . $method, $params);
    }

    protected function findModel(int $id): Model
    {
        return $this->model::find($id);
    }

    protected function findModelOrFail(int $id): Model
    {
        return $this->findModel($id) ?? throw new \Exception('');
    }
}
