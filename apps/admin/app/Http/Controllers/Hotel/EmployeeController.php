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
use App\Admin\Support\Http\Actions\DefaultDestroyAction;
use App\Admin\Support\Http\Actions\DefaultFormCreateAction;
use App\Admin\Support\Http\Actions\DefaultFormEditAction;
use App\Admin\Support\Http\Actions\DefaultFormStoreAction;
use App\Admin\Support\Http\Actions\DefaultFormUpdateAction;
use App\Admin\Support\View\Form\Form as FormContract;
use App\Admin\Support\View\Grid\Grid as GridContract;
use App\Admin\Support\View\Layout as LayoutContract;
use App\Admin\View\Menus\HotelMenu;
use App\Core\Support\Http\Responses\AjaxResponseInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index(Request $request, Hotel $hotel): LayoutContract
    {
        $this->hotel($hotel);

        $query = Employee::whereHotelId($hotel->id);
        $grid = $this->gridFactory($hotel->id)->data($query);

        return Layout::title('Сотрудники отеля')
            ->view('default.grid.grid', [
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

        return (new DefaultFormCreateAction($this->formFactory($hotel->id)))
            ->handle('Новый сотрудник');
    }

    /**
     * @throws FormSubmitFailedException
     */
    public function store(Request $request, Hotel $hotel): RedirectResponse
    {
        return (new DefaultFormStoreAction($this->formFactory($hotel->id)))
            ->handle(Employee::class);
    }

    public function edit(Request $request, Hotel $hotel, Employee $employee)
    {
        $this->hotel($hotel);

        return (new DefaultFormEditAction( $this->formFactory($hotel->id)))
            ->deletable()
            ->handle($employee)
            ->view('hotel.employee.edit.edit', [
                'contacts' => $employee->contacts,
                'cancelUrl' => route('hotels.employee.index', $hotel),
                'contactsUrl' => route('hotels.employee.contacts.index', ['hotel' => $hotel, 'employee' => $employee]),
                'createUrl' => route('hotels.employee.contacts.create', ['hotel' => $hotel, 'employee' => $employee]),
                'deleteUrl' => route('hotels.employee.destroy', ['hotel' => $hotel, 'employee' => $employee])
            ]);
    }

    public function update(Hotel $hotel, Employee $employee): RedirectResponse
    {
        return (new DefaultFormUpdateAction($this->formFactory($hotel->id)))
            ->handle($employee);
    }

    public function destroy(Hotel $hotel, Employee $employee): AjaxResponseInterface
    {
        return (new DefaultDestroyAction())->handle($employee);
    }

    protected function formFactory(int $hotelId): FormContract
    {
        return Form::name('data')
            ->hidden('hotel_id', ['value' => $hotelId])
            ->text('fullname', ['label' => 'ФИО', 'required' => true])
            ->text('department', ['label' => 'Отдел', 'required' => true])
            ->text('post', ['label' => 'Должность', 'required' => true]);
    }

    protected function gridFactory(int $hotelId): GridContract
    {
        return Grid::paginator(16)
            ->edit(
                fn(Employee $employee) => route(
                    'hotels.employee.edit',
                    ['hotel' => $hotelId, 'employee' => $employee->id]
                )
            )
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
