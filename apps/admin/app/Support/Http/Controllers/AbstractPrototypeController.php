<?php

namespace App\Admin\Support\Http\Controllers;

use App\Admin\Components\Factory\Prototype;
use App\Admin\Http\Controllers\Controller;
use App\Admin\Support\Facades\Breadcrumb;
use App\Admin\Support\Facades\Layout;
use App\Admin\Support\Facades\Prototypes;
use App\Admin\Support\Repository\RepositoryInterface;
use App\Admin\Support\View\Form\Form;
use App\Admin\Support\View\Grid\Grid;
use App\Admin\Support\View\Layout as LayoutContract;
use Illuminate\Support\Facades\Route;

abstract class AbstractPrototypeController extends Controller
{
    public const GRID_LIMIT = 16;

    protected Prototype $prototype;

    protected RepositoryInterface $repository;

    public function __construct()
    {
        $this->prototype = Prototypes::get($this->getPrototypeKey());
        $this->repository = $this->prototype->makeRepository();
    }

    public function index(): LayoutContract
    {
        Breadcrumb::prototype($this->prototype);

        $grid = $this->gridFactory();

        $grid->data($this->repository->queryWithCriteria($grid->getSearchCriteria()));

        return Layout::title($this->prototype->title('index'))
            ->view($this->prototype->view('index') ?? $this->prototype->view('grid') ?? 'default.grid', [
                'quicksearch' => $grid->getQuicksearch(),
                'searchForm' => $grid->getSearchForm(),
                'grid' => $grid,
                'paginator' => $grid->getPaginator(),
                'createUrl' => $this->prototype->hasPermission('create') ? $this->prototype->route('create') : null
            ]);
    }

    public function show(int $id): LayoutContract
    {
        $model = $this->repository->findOrFail($id);
        $title = (string)$model;//$this->prototype->title('show')

        Breadcrumb::prototype($this->prototype)
            ->add($title);

        return Layout::title($title)
            ->view($this->prototype->view('show'), [
                'model' => $model
            ]);
    }

    public function create(): LayoutContract
    {
        Breadcrumb::prototype($this->prototype)
            ->add($this->prototype->title('create') ?? 'Новая запись');

        $form = $this->formFactory()
            ->method('post')
            ->action($this->prototype->route('store'));

        //TODO back button

        return Layout::title($this->prototype->title('create'))
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

    public function edit(int $id): LayoutContract
    {
        $model = $this->repository->findOrFail($id);

        $title = (string)$model;
        $breadcrumbs = Breadcrumb::prototype($this->prototype);
        if ($this->hasShowAction()) {
            $breadcrumbs->addUrl($this->prototype->route('show'), $title);
        } else {
            $breadcrumbs->add($title);
        }
        $breadcrumbs->add($this->prototype->title('edit') ?? 'Редактирование');

        $form = $this->formFactory()
            ->method('put')
            ->action($this->prototype->route('update', $model->id))
            ->data($model);

        return Layout::title($title)
            ->view($this->prototype->view('edit') ?? $this->prototype->view('form') ?? 'default.form', [
                'model' => $model,
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

        $this->repository->update($model->id, $form->getData());

        return redirect($this->prototype->route('index'));
    }

    public function destroy(int $id)
    {
        $this->repository->delete($id);

        return redirect($this->prototype->route('index'));
    }

    protected function gridFactory(): Grid
    {
        throw new \LogicException('Please implement the gridFactory method on your controller.');
    }

    protected function formFactory(): Form
    {
        throw new \LogicException('Please implement the formFactory method on your controller.');
    }

    protected function hasShowAction(): bool
    {
        return Route::has($this->prototype->routeName('show'));
    }

    abstract protected function getPrototypeKey(): string;
}
