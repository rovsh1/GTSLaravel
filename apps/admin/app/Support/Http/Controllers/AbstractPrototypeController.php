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
use App\Core\Support\Http\Responses\AjaxErrorResponse;
use App\Core\Support\Http\Responses\AjaxRedirectResponse;
use App\Core\Support\Http\Responses\AjaxResponseInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Route;

abstract class AbstractPrototypeController extends Controller
{
    public const GRID_LIMIT = 16;

    protected Prototype $prototype;

    protected RepositoryInterface $repository;

    protected Model $model;

    public function __construct()
    {
        $this->prototype = Prototypes::get($this->getPrototypeKey());
        $this->repository = $this->prototype->makeRepository();
    }

    public function index(): LayoutContract
    {
        Breadcrumb::prototype($this->prototype);

        $grid = $this->gridFactory();
        $query = $this->repository->queryWithCriteria($grid->getSearchCriteria());
        $this->prepareGridQuery($query);
        $grid->data($query);

        return Layout::title($this->prototype->title('index'))
            ->view($this->prototype->view('index') ?? $this->prototype->view('grid') ?? 'default.grid.grid', [
                'quicksearch' => $grid->getQuicksearch(),
                'searchForm' => $grid->getSearchForm(),
                'grid' => $grid,
                'paginator' => $grid->getPaginator(),
                'createUrl' => $this->prototype->hasPermission('create') ? $this->prototype->route('create') : null
            ]);
    }

    public function show(int $id): LayoutContract
    {
        $this->model = $this->repository->findOrFail($id);
        $title = (string)$this->model;//$this->prototype->title('show')

        Breadcrumb::prototype($this->prototype)
            ->add($title);

        $this->prepareShowMenu($this->model);

        return Layout::title($title)
            ->view($this->prototype->view('show') ?? ($this->getPrototypeKey() . '.show'), [
                'model' => $this->model,
                'editUrl' => $this->prototype->hasPermission('update') ? $this->prototype->route(
                    'edit',
                    $this->model->id
                ) : null,
                'deleteUrl' => $this->prototype->hasPermission('delete') ? $this->prototype->route(
                    'destroy',
                    $this->model->id
                ) : null,
                ...$this->getShowViewData()
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
            ->view($this->prototype->view('create') ?? $this->prototype->view('form') ?? 'default.form.form', [
                'form' => $form,
                'cancelUrl' => $this->prototype->route('index')
            ]);
    }

    public function store(): RedirectResponse
    {
        $form = $this->formFactory()
            ->method('post');

        $form->trySubmit($this->prototype->route('create'));

        $preparedData = $this->saving($form->getData());
        $this->model = $this->repository->create($preparedData);

        if ($this->hasShowAction()) {
            return redirect($this->prototype->route('show', $this->model));
        } else {
            return redirect($this->prototype->route('index'));
        }
    }

    public function edit(int $id): LayoutContract
    {
        $breadcrumbs = Breadcrumb::prototype($this->prototype);

        $this->model = $model = $this->repository->findOrFail($id);

        $title = (string)$model;
        if ($this->hasShowAction()) {
            $breadcrumbs->addUrl($this->prototype->route('show', $model), $title);
        } else {
            $breadcrumbs->add($title);
        }
        $breadcrumbs->add($this->prototype->title('edit') ?? 'Редактирование');

        $form = $this->formFactory()
            ->method('put')
            ->action($this->prototype->route('update', $model->id))
            ->data($model);

        return Layout::title($title)
            ->view($this->prototype->view('edit') ?? $this->prototype->view('form') ?? 'default.form.form', [
                'model' => $model,
                'form' => $form,
                'cancelUrl' => $this->prototype->route('index'),
                'deleteUrl' => $this->prototype->hasPermission('delete') ? $this->prototype->route(
                    'destroy',
                    $model->id
                ) : null
            ]);
    }

    public function update(int $id): RedirectResponse
    {
        $this->model = $this->repository->findOrFail($id);

        $form = $this->formFactory()
            ->method('put');

        $form->trySubmit($this->prototype->route('edit', $this->model));

        $preparedData = $this->saving($form->getData());
        $this->repository->update($this->model->id, $preparedData);

        return redirect($this->prototype->route('index'));
    }

    public function destroy(int $id): AjaxResponseInterface
    {
        try {
            $this->repository->delete($id);
        } catch (\Throwable $e) {
            return new AjaxErrorResponse($e->getMessage());
        }

        return new AjaxRedirectResponse($this->prototype->route('index'));
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

    protected function getShowViewData(): array
    {
        throw new \LogicException('Please implement the getShowViewData method on your controller.');
    }

    protected function prepareGridQuery(Builder $query) {}

    protected function prepareShowMenu(Model $model) {}

    protected function saving(array $data): array
    {
        return $data;
    }

    abstract protected function getPrototypeKey(): string;
}
