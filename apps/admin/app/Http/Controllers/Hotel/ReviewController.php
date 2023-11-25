<?php

namespace App\Admin\Http\Controllers\Hotel;

use App\Admin\Components\Factory\Prototype;
use App\Admin\Http\Controllers\Controller;
use App\Admin\Models\Hotel\Hotel;
use App\Admin\Models\Hotel\Review;
use App\Admin\Models\Hotel\ReviewRating;
use App\Admin\Support\Facades\Acl;
use App\Admin\Support\Facades\Breadcrumb;
use App\Admin\Support\Facades\Form;
use App\Admin\Support\Facades\Grid;
use App\Admin\Support\Facades\Layout;
use App\Admin\Support\Facades\Prototypes;
use App\Admin\Support\Facades\Sidebar;
use App\Admin\Support\Http\Actions\DefaultDestroyAction;
use App\Admin\Support\Http\Actions\DefaultFormCreateAction;
use App\Admin\Support\View\Form\Form as FormContract;
use App\Admin\Support\View\Grid\Grid as GridContract;
use App\Admin\Support\View\Layout as LayoutContract;
use App\Admin\View\Menus\HotelMenu;
use App\Shared\Http\Responses\AjaxResponseInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Module\Shared\Contracts\Service\TranslatorInterface;
use Module\Shared\Enum\Hotel\ReviewRatingTypeEnum;
use Module\Shared\Enum\Hotel\ReviewStatusEnum;

class ReviewController extends Controller
{
    private Prototype $prototype;

    public function __construct(
        private readonly TranslatorInterface $translator
    ) {
        $this->prototype = Prototypes::get('hotel');
    }

    public function index(Request $request, Hotel $hotel): LayoutContract
    {
        $this->hotel($hotel);

        $grid = $this->gridFactory($hotel);
        $query = Review::whereHotelId($hotel->id)->applyCriteria($grid->getSearchCriteria());
        $grid->data($query);

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
        $form = $this->formFactory($hotel);

        $form->submitOrFail($this->prototype->route('reviews.create', $hotel));

        $data = $form->getData();
        $ratingFields = $this->getOnlyRatingFields($data);
        $avgRating = $this->getCalculatedReviewRating($ratingFields);
        $review = Review::create([
            ...$data,
            'rating' => $avgRating
        ]);
        $this->createReviewRatings($review->id, $ratingFields);

        return redirect(
            $this->prototype->route('reviews.index', $hotel)
        );
    }

    public function edit(Request $request, Hotel $hotel, Review $review): LayoutContract
    {
        $this->hotel($hotel);

        $reviewRatings = $review->ratings->mapWithKeys(
            fn(ReviewRating $rating) => ['rating_' . $rating->type->value => $rating->value]
        )->all();
        $preparedReviewData = [
            ...$review->toArray(),
            ...$reviewRatings
        ];
        $form = $this->formFactory($hotel)
            ->method('put')
            ->action($this->prototype->route('reviews.update', [$hotel, $review]))
            ->data($preparedReviewData);

        return Layout::title((string)$review)
            ->view('default.form.form', [
                'form' => $form,
                'cancelUrl' => $this->prototype->route('reviews.index', $hotel),
                'deleteUrl' => $this->prototype->route('reviews.destroy', [$hotel, $review]),
            ]);
    }

    public function update(Hotel $hotel, Review $review): RedirectResponse
    {
        $form = $this->formFactory($hotel)->method('put');

        $form->submitOrFail($this->prototype->route('reviews.update', [$hotel, $review]));

        $data = $form->getData();
        $ratingFields = $this->getOnlyRatingFields($data);
        $avgRating = $this->getCalculatedReviewRating($ratingFields);
        $review->update([
            ...$data,
            'rating' => $avgRating
        ]);
        $this->createReviewRatings($review->id, $ratingFields);

        return redirect(
            $this->prototype->route('reviews.index', $hotel)
        );
    }

    public function destroy(Hotel $hotel, Review $review): AjaxResponseInterface
    {
        return (new DefaultDestroyAction())->handle($review);
    }

    private function getOnlyRatingFields(array $data): array
    {
        return array_filter($data, fn(string $key) => strpos($key, 'rating_') !== false, ARRAY_FILTER_USE_KEY);
    }

    private function getCalculatedReviewRating(array $ratingData): float
    {
        $reviewRating = 0;
        foreach ($ratingData as $rating => $value) {
            $ratingTypeEnum = $this->getRatingTypeEnumFromFieldName($rating);
            $calculatedRating = $ratingTypeEnum->calculateValue($value);
            $reviewRating += $calculatedRating;
        }

        return $reviewRating;
    }

    private function createReviewRatings(int $reviewId, array $ratingData): void
    {
        ReviewRating::whereReviewId($reviewId)->delete();
        foreach ($ratingData as $rating => $value) {
            $ratingTypeEnum = $this->getRatingTypeEnumFromFieldName($rating);
            ReviewRating::create([
                'review_id' => $reviewId,
                'type' => $ratingTypeEnum,
                'value' => $value,
            ]);
        }
    }

    private function getRatingTypeEnumFromFieldName(string $name): ReviewRatingTypeEnum
    {
        $ratingType = Str::afterLast($name, 'rating_');

        return ReviewRatingTypeEnum::from($ratingType);
    }

    protected function formFactory(Hotel $hotel): FormContract
    {
        $form = Form::name('data')
            ->hidden('hotel_id', ['value' => $hotel->id])
            ->text('name', ['label' => 'Имя', 'required' => true])
            ->textarea('text', ['label' => 'Текст отзыва', 'required' => true]);

        $ratingFieldSettingsGetter = fn(ReviewRatingTypeEnum $enum) => [
            'rating_' . $enum->value,
            [
                'items' => [['value' => 1], ['value' => 2], ['value' => 3], ['value' => 4], ['value' => 5]],
                'required' => true,
                'label' => $this->translator->translateEnum($enum)
            ]
        ];
        foreach (ReviewRatingTypeEnum::cases() as $ratingType) {
            $form->radio(...$ratingFieldSettingsGetter($ratingType));
        }

        return $form->enum('status', ['label' => 'Статус', 'required' => true, 'enum' => ReviewStatusEnum::class]);
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
