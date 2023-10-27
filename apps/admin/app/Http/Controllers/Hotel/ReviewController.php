<?php

namespace App\Admin\Http\Controllers\Hotel;

use App\Admin\Components\Factory\Prototype;
use App\Admin\Http\Controllers\Controller;
use App\Admin\Models\Hotel\Hotel;
use App\Admin\Models\Hotel\Review;
use App\Admin\Support\Facades\Acl;
use App\Admin\Support\Facades\Breadcrumb;
use App\Admin\Support\Facades\Form;
use App\Admin\Support\Facades\Grid;
use App\Admin\Support\Facades\Layout;
use App\Admin\Support\Facades\Prototypes;
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
use Module\Shared\Enum\Hotel\ReviewStatusEnum;

class ReviewController extends Controller
{
    private Prototype $prototype;

    public function __construct()
    {
        $this->prototype = Prototypes::get('hotel');
    }

    public function index(Request $request, Hotel $hotel): LayoutContract
    {
        $this->hotel($hotel);

        $query = Review::whereHotelId($hotel->id);
        $grid = $this->gridFactory($hotel)->data($query);

        return Layout::title('Отзывы')
            ->view('default.grid.grid', [
                'quicksearch' => $grid->getQuicksearch(),
                'grid' => $grid,
                'createUrl' => Acl::isUpdateAllowed($this->prototype->key)
                    ? $this->prototype->route('reviews.create', $hotel)
                    : null,
            ]);
    }

    public function create(Request $request, Hotel $hotel): LayoutContract
    {
        $this->hotel($hotel);

        return (new DefaultFormCreateAction($this->formFactory($hotel)))
            ->handle('Новый отзыв');
    }

    public function store(Request $request, Hotel $hotel): RedirectResponse
    {
        return (new DefaultFormStoreAction($this->formFactory($hotel)))
            ->handle(Review::class);
    }

    public function edit(Request $request, Hotel $hotel, Review $review): LayoutContract
    {
        $this->hotel($hotel);

        return (new DefaultFormEditAction($this->formFactory($hotel)))
            ->deletable()
            ->handle($review);
    }

    public function update(Hotel $hotel, Review $review): RedirectResponse
    {
        return (new DefaultFormUpdateAction($this->formFactory($hotel)))
            ->handle($review);
    }

    public function destroy(Hotel $hotel, Review $review): AjaxResponseInterface
    {
        return (new DefaultDestroyAction())->handle($review);
    }

    protected function formFactory(Hotel $hotel): FormContract
    {
        return Form::name('data')
            ->hidden('hotel_id', ['value' => $hotel->id])
            ->text('name', ['label' => 'Имя', 'required' => true])
            ->textarea('text', ['label' => 'Текст отзыва', 'required' => true])
            ->enum('status', ['label' => 'Статус', 'required' => true, 'enum' => ReviewStatusEnum::class])
            ->hidden('rating', ['value' => 0]);
    }

    protected function gridFactory(Hotel $hotel): GridContract
    {
        return Grid::paginator(16)
            ->enableQuicksearch()
            ->edit(fn($review) => $this->prototype->route('reviews.edit', [$hotel, $review]))
            ->text('name', ['text' => 'Имя автора'])
            ->number('rating', ['text' => 'Рейтинг', 'order' => true])
            ->enum('status', ['text' => 'Статус', 'order' => true, 'enum' => ReviewStatusEnum::class]);
    }

    private function hotel(Hotel $hotel): void
    {
        Breadcrumb::prototype('hotel')
            ->addUrl(route('hotels.show', $hotel), (string)$hotel)
            ->addUrl(route('hotels.reviews.index', $hotel), 'Отзывы');

        Sidebar::submenu(new HotelMenu($hotel, 'reviews'));
    }
}
