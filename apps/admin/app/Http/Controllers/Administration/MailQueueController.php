<?php

namespace App\Admin\Http\Controllers\Administration;

use App\Admin\Components\Factory\Prototype;
use App\Admin\Http\Controllers\Controller;
use App\Admin\Support\Facades\Breadcrumb;
use App\Admin\Support\Facades\Grid;
use App\Admin\Support\Facades\Layout;
use App\Admin\Support\Facades\Prototypes;
use App\Admin\Support\View\Grid\Grid as GridContract;
use App\Admin\Support\View\Layout as LayoutContract;
use App\Shared\Support\Facades\MailAdapter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Pkg\MailManager\Model\QueueMessage;

class MailQueueController extends Controller
{
    protected Prototype $prototype;

    public function __construct()
    {
        $this->prototype = Prototypes::get('mail-queue');
    }

    public function index(): LayoutContract
    {
        Breadcrumb::prototype($this->prototype);

        $grid = $this->gridFactory();
        $data = QueueMessage::query()
            ->orderBy('priority', 'desc')
            ->orderBy('created_at', 'desc');

        $grid->data($data);

        return Layout::title($this->prototype->title('index'))
            ->view('administration.mail-queue.mail-queue', [
                'quicksearch' => $grid->getQuicksearch(),
                //'searchForm' => $grid->getSearchForm(),
                'grid' => $grid,
                'paginator' => $grid->getPaginator()
            ]);
    }

    public function sendTest(Request $request): JsonResponse
    {
        MailAdapter::sendTo(
            $request->query('to', 'test@gts.com'),
            'Test message',
            'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc egestas vulputate turpis in ornare. Fusce quam urna, vulputate ac condimentum sed, facilisis quis enim. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer vel pulvinar magna. Quisque euismod, lacus sit amet pulvinar fringilla, arcu magna faucibus ligula, ac ultrices nunc odio in orci. Cras dapibus venenatis pellentesque. Vestibulum fermentum sapien massa, a convallis mauris fermentum vel. Sed et rutrum lacus. Quisque velit metus, pharetra vitae massa sit amet, euismod posuere lacus. Praesent feugiat porttitor lorem quis vehicula. Nam ut urna enim. Nullam eu sem odio. Quisque sapien odio, cursus at fringilla et, tristique at leo.',
            [
//                new FileDto(
//                    'cac4c13561456e339e71e532fd864c5c',
//                    'Screenshot%202023-08-02%20165250.png',
//                    'http://www.gts.local/file/ca/c4/cac4c13561456e339e71e532fd864c5c/Screenshot%202023-08-02%20165250.png'
//                )
            ]
        );

        return response()->json(['status' => 'ok']);
    }

    protected function gridFactory(): GridContract
    {
        $statuses = [
            1 => 'Waiting',
            2 => 'Processing',
            3 => 'Sent',
            4 => 'Failed'
        ];

        return Grid::enableQuicksearch()
            ->paginator(10)
//            ->text('uuid', ['text' => 'UUID'])
            ->text('subject', ['text' => 'Тема письма'])
            ->text('priority', ['text' => 'Приоритет', 'order' => true])
            //->text('attempts', ['text' => 'Попыток', 'order' => true])
            ->text('status', [
                'text' => 'Статус',
                'renderer' => fn($r) => $statuses[$r->status],
                'order' => true
            ])
            ->text('payload', [
                'text' => 'Данные',
                'renderer' => fn($r) => self::printJson('description', $r->payload)
            ])
            ->text('context', [
                'text' => 'Контекст',
                'renderer' => fn($r) => self::printJson('info', $r->context)
            ])
            ->date('created_at', ['text' => 'Создан', 'format' => 'datetime', 'order' => true])
            ->actions([
                //TODO retry, resend actions
                'actions' => []
            ])
            ->orderBy('created_at', 'desc');
    }

    private static function printJson(string $icon, array|null $data): string
    {
        $str = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        return '<div class="btn-data-content" data-content="' . htmlspecialchars($str) . '">'
            . '<i class="icon">' . $icon . '</i>'
            . '</div>';
    }
}
