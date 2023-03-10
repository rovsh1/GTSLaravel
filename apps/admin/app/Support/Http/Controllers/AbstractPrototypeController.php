<?php

namespace App\Admin\Support\Http\Controllers;

use App\Admin\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;

abstract class AbstractPrototypeController extends Controller
{
    public const GRID_LIMIT = 20;

    protected $prototype;

    protected $repository;

    protected $form;

    protected $grid;

    public function __construct()
    {
        $this->prototype = app('prototypes')->get($this->prototype);
        $this->repository = $this->prototype->makeRepository();
    }

    public function index()
    {
        $this->breadcrumbs();

        $grid = $this->gridFactory();

        $grid->data($this->repository->queryWithCriteria($grid->getSearchCriteria()));

        $this->buildGridActions(app('menu.actions'));

        return app('layout')
            ->title($this->prototype->title('index'))
            ->view($this->prototype->view('index') ?? $this->prototype->view('grid') ?? 'default.grid', [
                'quicksearch' => $grid->getQuicksearch(),
                'searchForm' => $grid->getSearchForm(),
                'grid' => $grid,
                'paginator' => $grid->getPaginator()
            ]);
    }

    public function show(int $id)
    {
        $model = $this->repository->findOrFail($id);
        $title = (string)$model;//$this->prototype->title('show')

        $this->breadcrumbs()
            ->add($title);

        $this->buildShowActions(app('menu.actions'), $model);

        return app('layout')
            ->title($title)
            ->view($this->prototype->view('show'), $this->getShowViewData($model));
    }

    public function create()
    {
        $this->breadcrumbs()
            ->add($this->prototype->title('create') ?? 'Новая запись');

        $form = $this->formFactory()
            ->method('post')
            ->action($this->prototype->route('store'));

        //TODO back button

        return app('layout')
            ->title($this->prototype->title('create'))
            ->view($this->prototype->view('create') ?? $this->prototype->view('form') ?? 'default.form', [
                'form' => $form,
                'cancelUrl' => $this->prototype->route('index')
            ]);
    }

    public function store()
    {
        $form = $this->formFactory()
            ->method('post');

        if (!$form->submit()) {
            return redirect($this->prototype->route('create'))
                ->withErrors($form->errors())
                ->withInput();
        }

        $model = $this->repository->create($form->getData());

        return redirect($this->prototype->route('index'));
    }

    public function edit(int $id)
    {
        $model = $this->repository->findOrFail($id);

        $title = (string)$model;
        $breadcrumbs = $this->breadcrumbs();
        if ($this->hasShowAction()) {
            $breadcrumbs->addUrl($this->prototype->route('show'), $title);
        } else {
            $breadcrumbs->add($title);
        }
        $breadcrumbs->add($this->prototype->title('edit') ?? 'Редактирование');

        $form = $this->formFactory()
            ->method('put')
            ->action($this->prototype->route('update', $id))
            ->data($model);

        return app('layout')
            ->title($title)
            ->view($this->prototype->view('edit') ?? $this->prototype->view('form') ?? 'default.form', [
                'form' => $form,
                'cancelUrl' => $this->prototype->route('index'),
                'deleteUrl' => $this->prototype->hasPermission('delete') ? $this->prototype->route('destroy', $model->id) : null
            ]);
    }

    public function update(int $id)
    {
        $model = $this->repository->findOrFail($id);

        $form = $this->formFactory()
            ->method('put');

        if (!$form->submit()) {
            return redirect($this->prototype->route('edit', $model))
                ->withErrors($form->errors())
                ->withInput();
        }

        $this->repository->update($model, $form->getData());

        return redirect($this->prototype->route('index'));
    }

    public function destroy(int $id)
    {
        $this->repository->delete($id);

        return redirect($this->prototype->route('index'));
    }

    protected function gridFactory()
    {
        return new $this->grid();
    }

    protected function buildGridActions($menu)
    {
        if ($this->prototype->hasPermission('create')) {
            $menu->addUrl(
                $this->prototype->route('create'),
                $this->prototype->title('add') ?? $this->prototype->title('create') ?? 'Добавить'
            );
        }
    }

    protected function buildShowActions($menu, $model)
    {
        if ($this->prototype->hasPermission('delete')) {
            $menu->addUrl(
                $this->prototype->route('create'),
                $this->prototype->title('delete') ?? 'Удалить'
            );
        }
    }

    protected function formFactory()
    {
        return new $this->form('data');
    }

    protected function getShowViewData($model)
    {
        return [
            'model' => $model
        ];
    }

    protected function breadcrumbs()
    {
        return app('breadcrumbs')
            ->addUrl($this->prototype->route('index'), $this->prototype->title('index') ?? 'Default index');
    }

    protected function hasShowAction(): bool
    {
        return Route::has($this->prototype->routeName('show'));
    }
}
