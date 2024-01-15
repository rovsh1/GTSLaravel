<?php

namespace App\Admin\Http\Controllers\Booking\Hotel;

use App\Admin\Components\Factory\Prototype;
use App\Admin\Http\Controllers\Controller;
use App\Admin\Support\Facades\Booking\BookingAdapter;
use App\Admin\Support\Facades\Breadcrumb;
use App\Admin\Support\Facades\Layout;
use App\Admin\Support\Facades\Prototypes;
use App\Admin\Support\View\Layout as LayoutContract;
use Pkg\Booking\EventSourcing\Application\UseCase\GetHistory;

class TimelineController extends Controller
{
    protected Prototype $prototype;

    public function __construct()
    {
        $this->prototype = Prototypes::get('hotel-booking');
    }

    public function index(int $id): LayoutContract
    {
        $booking = BookingAdapter::getBooking($id);

        $title = "Бронь №{$id}";
        Breadcrumb::prototype($this->prototype)
            ->addUrl(route('hotel-booking.show', $id), $title)
            ->add('История изменений');

        return Layout::title($title)
            ->view('booking.hotel.timeline.timeline', [
                'history' => app(GetHistory::class)->execute($id)
            ]);
    }
}
