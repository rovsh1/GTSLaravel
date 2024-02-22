<?php

namespace App\Admin\Support\Http\Controllers;

use App\Admin\Components\Factory\Prototype;
use App\Admin\Http\Controllers\Controller;
use App\Admin\Support\Facades\Acl;
use App\Admin\Support\Facades\Breadcrumb;
use App\Admin\Support\Facades\Layout;
use App\Admin\Support\Facades\Prototypes;
use App\Admin\Support\Repository\RepositoryInterface;
use App\Admin\Support\View\Form\Form;
use App\Admin\Support\View\Grid\Grid;
use App\Admin\Support\View\Layout as LayoutContract;
use App\Shared\Http\Responses\AjaxRedirectResponse;
use App\Shared\Http\Responses\AjaxResponseInterface;
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

    protected Form $form;

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
                'createUrl' => $this->isAllowed('create') ? $this->prototype->route('create') : null
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
                'editUrl' => $this->isAllowed('update') ? $this->prototype->route(
                    'edit',
                    $this->model->id
                ) : null,
                'deleteUrl' => $this->isAllowed('delete') ? $this->prototype->route(
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

        $form = $this->form()
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
        $form = $this->form()
            ->method('post')
            ->failUrl($this->prototype->route('create'));

        $form->submitOrFail();

        $preparedData = $this->saving($form->getData());
        $this->model = $this->repository->create($preparedData);

        $redirectUrl = $this->prototype->route('index');
        if ($this->hasShowAction()) {
            $redirectUrl = $this->prototype->route('show', $this->model);
        }

        return redirect($redirectUrl);
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

        $form = $this->form()
            ->method('put')
            ->action($this->prototype->route('update', $model->id));

        $this->formFill($form, $model);
        $this->prepareEditMenu($this->model);

        return Layout::title($title)
            ->view($this->prototype->view('edit') ?? $this->prototype->view('form') ?? 'default.form.form', [
                'model' => $model,
                'form' => $form,
                'cancelUrl' => $this->hasShowAction()
                    ? $this->prototype->route('show', $model)
                    : $this->prototype->route('index'),
                'deleteUrl' => $this->isAllowed('delete')
                    ? $this->prototype->route('destroy', $model->id)
                    : null
            ]);
    }

    public function update(int $id): RedirectResponse
    {
        $this->model = $this->repository->findOrFail($id);

        $form = $this->form()
            ->method('put')
            ->failUrl($this->prototype->route('edit', $this->model));

        $form->submitOrFail();

        $preparedData = $this->saving($form->getData());
        $this->repository->update($this->model->id, $preparedData);

        $redirectUrl = $this->prototype->route('index');
        if ($this->hasShowAction()) {
            $redirectUrl = $this->prototype->route('show', $this->model);
        }

        return redirect($redirectUrl);
    }

    public function destroy(int $id): AjaxResponseInterface
    {
        $this->repository->delete($id);

        return new AjaxRedirectResponse($this->prototype->route('index'));
    }

    protected function gridFactory(): Grid
    {
        throw new \LogicException('Please implement the gridFactory method on your controller.');
    }

    protected function form(): Form
    {
        if (isset($this->form)) {
            return $this->form;
        }

        return $this->form = $this->formFactory();
    }

    protected function formFactory(): Form
    {
        throw new \LogicException('Please implement the formFactory method on your controller.');
    }

    protected function formFill(Form $form, Model $model): void
    {
        $form->data($model);
    }

    protected function hasShowAction(): bool
    {
        return Route::has($this->prototype->routeName('show'));
    }

    protected function getShowViewData(): array
    {
        throw new \LogicException('Please implement the getShowViewData method on your controller.');
    }

    protected function prepareGridQuery(Builder $query)
    {
    }

    protected function prepareShowMenu(Model $model)
    {
    }

    protected function prepareEditMenu(Model $model)
    {
    }

    protected function saving(array $data): array
    {
        return $data;
    }

    protected function isAllowed(string $permission): bool
    {
        return $this->prototype->hasPermission($permission) && Acl::isAllowed($this->prototype->key, $permission);
    }

    abstract protected function getPrototypeKey(): string;
}
