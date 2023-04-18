<?php

namespace App\Admin\Http\Controllers\Hotel;

use App\Admin\Exceptions\FormSubmitFailedException;
use App\Admin\Http\Controllers\Controller;
use App\Admin\Models\Hotel\Employee;
use App\Admin\Models\Hotel\Hotel;
use App\Admin\Support\Facades\Acl;
use App\Admin\Support\Facades\Breadcrumb;
use App\Admin\Support\Facades\Form;
use App\Admin\Support\Facades\Grid;
use App\Admin\Support\Facades\Layout;
use App\Admin\Support\Facades\Sidebar;
use App\Admin\Support\View\Form\Form as FormContract;
use App\Admin\Support\View\Grid\Grid as GridContract;
use App\Admin\Support\View\Layout as LayoutContract;
use App\Admin\View\Menus\HotelMenu;
use App\Core\Support\Http\Responses\AjaxErrorResponse;
use App\Core\Support\Http\Responses\AjaxRedirectResponse;
use App\Core\Support\Http\Responses\AjaxResponseInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index(Request $request, Hotel $hotel): LayoutContract
    {
        $this->hotel($hotel);

        $query = Employee::query()->where('hotel_id', $hotel->id);
        $grid = $this->gridFactory()->data($query);

        return Layout::title('Сотрудники отеля')
            ->addMetaName('hotel-employee-base-route', route('hotels.employee.index', $hotel))
            ->style('default/grid')
            ->script('hotel/employee/main')
            ->view('default.grid', [
                'quicksearch' => $grid->getQuicksearch(),
                'searchForm' => $grid->getSearchForm(),
                'paginator' => $grid->getPaginator(),
                'grid' => $grid,
                'hotel' => $hotel,
                'editAllowed' => $this->isUpdateAllowed(),
                'deleteAllowed' => $this->isUpdateAllowed(),
                'createUrl' => $this->isUpdateAllowed() ? route('hotels.employee.create', $hotel) : null,
            ]);
    }

    public function create(Request $request, Hotel $hotel): LayoutContract
    {
        $this->hotel($hotel);

        Breadcrumb::add('Новый сотрудник');

        $form = $this->formFactory($hotel->id)
            ->method('post')
            ->action(route('hotels.employee.store', $hotel));

        return Layout::title('Новый сотрудник')
            ->style('default/form')
            ->view('default.form', [
                'form' => $form,
                'cancelUrl' => route('hotels.employee.index', $hotel)
            ]);
    }

    /**
     * @throws FormSubmitFailedException
     */
    public function store(Request $request, Hotel $hotel): RedirectResponse
    {
        $form = $this->formFactory($hotel->id)
            ->method('post');

        $form->trySubmit(route('hotels.employee.create', $hotel));

        $data = $form->getData();
        Employee::create($data);

        return redirect(route('hotels.employee.index', $hotel));
    }

    public function edit(Request $request, Hotel $hotel, Employee $employee)
    {
        $this->hotel($hotel);

        Breadcrumb::add($employee->fullname);

        $form = $this->formFactory($hotel->id)
            ->method('post')
            ->action(route('hotels.employee.update', ['hotel' => $hotel, 'employee' => $employee]))
            ->data($employee);

        return Layout::title($employee->fullname)
            ->style('default/form')
            ->script('hotel/employee/main')
            ->view('hotel.employee.edit', [
                'form' => $form,
                'cancelUrl' => route('hotels.employee.index', $hotel),
                'contacts' => $employee->contacts,
                'contactsUrl' => $this->isUpdateAllowed()
                    ? route(
                        'hotels.employee.contacts.index',
                        ['hotel' => $hotel, 'employee' => $employee]
                    )
                    : null,
                'createUrl' => $this->isUpdateAllowed()
                    ? route(
                        'hotels.employee.contacts.create',
                        ['hotel' => $hotel, 'employee' => $employee]
                    )
                    : null,
                'deleteUrl' => $this->isUpdateAllowed()
                    ? route(
                        'hotels.employee.destroy',
                        ['hotel' => $hotel, 'employee' => $employee]
                    )
                    : null,
            ]);
    }

    public function destroy(Hotel $hotel, Employee $employee): AjaxResponseInterface
    {
        try {
            $employee->delete();
        } catch (\Throwable $e) {
            return new AjaxErrorResponse($e->getMessage());
        }

        return new AjaxRedirectResponse(route('hotels.employee.index', $hotel));
    }

    protected function formFactory(int $hotelId): FormContract
    {
        return Form::name('data')
            ->hidden('hotel_id', ['value' => $hotelId])
            ->text('fullname', ['label' => 'ФИО', 'required' => true])
            ->text('department', ['label' => 'Отдел', 'required' => true])
            ->text('post', ['label' => 'Должность', 'required' => true]);
    }

    protected function gridFactory(): GridContract
    {
        return Grid::paginator(16)
            ->setOption('id','hotel-employee-grid')
            ->text('fullname', ['text' => 'ФИО', 'order' => true])
            ->text('department', ['text' => 'Отдел'])
            ->text('post', ['text' => 'Должность']);
    }

    private function hotel(Hotel $hotel): void
    {
        Breadcrumb::prototype('hotel')
            ->addUrl(route('hotels.show', $hotel), (string)$hotel)
            ->addUrl(route('hotels.employee.index', $hotel), 'Сотрудники');

        Sidebar::submenu(new HotelMenu($hotel, 'employees'));
    }

    private function isUpdateAllowed(): bool
    {
        return Acl::isUpdateAllowed('hotel');
    }
}
