<?php

namespace App\Admin\Http\Controllers\Administration;

use App\Admin\Components\Factory\Prototype;
use App\Admin\Http\Controllers\Controller;
use App\Admin\Services\JournalLogger\EventTypeEnum;
use App\Admin\Support\Facades\Breadcrumb;
use App\Admin\Support\Facades\Format;
use App\Admin\Support\Facades\Grid;
use App\Admin\Support\Facades\Layout;
use App\Admin\Support\Facades\Prototypes;
use App\Admin\Support\View\Grid\Grid as GridContract;
use App\Admin\Support\View\Grid\SearchForm;
use App\Admin\Support\View\Layout as LayoutContract;
use Carbon\CarbonPeriod;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class JournalController extends Controller
{
    protected Prototype $prototype;

    public function __construct()
    {
        $this->prototype = Prototypes::get('administrator-journal');
    }

    public function index(): LayoutContract
    {
        Breadcrumb::prototype($this->prototype);

        $grid = $this->gridFactory();

        $grid->data($this->buildQuery($grid->getSearchCriteria()));

        return Layout::title($this->prototype->title('index'))
            ->view('administration.journal.journal', [
                'quicksearch' => $grid->getQuicksearch(),
                'searchForm' => $grid->getSearchForm(),
                'grid' => $grid,
                'paginator' => $grid->getPaginator()
            ]);
    }

    private function buildQuery(array $filters)
    {
        return DB::table('administrator_journal_log')
            ->when($filters['event'] ?? null, function ($q, $v) {
                $q->where('event', $v);
            })
            ->when($filters['entity_id'] ?? null, function ($q, $v) {
                $q->where('entity_id', $v);
            })
            ->when($filters['entity_class'] ?? null, function ($q, $v) {
                $q->where('entity_class', 'like', "%$v%");
            })
            ->when($filters['created_at'] ?? null, function (Builder $q, CarbonPeriod $v) {
                $q->whereBetween('created_at', [
                    $v->getStartDate()->startOfDay(),
                    $v->getEndDate()->endOfDay()
                ]);
            })
            ->when($filters['quicksearch'] ?? null, function (Builder $q, string $v) {
                $q->where('entity_class', 'like', "%$v%")
                    ->orWhere('event', 'like', "%$v%");
            });
    }

    private function buildSearchForm()
    {
        return (new SearchForm())
            ->dateRange('created_at', ['label' => 'Дата'])
            ->enum('event', [
                'label' => 'Событие',
                'enum' => EventTypeEnum::class,
                'emptyItem' => __('select-all')
            ])
            ->text('entity_id', ['label' => 'EntityId'])
            ->text('entity_class', ['label' => 'EntityClass']);
    }

    private function gridFactory(): GridContract
    {
        return Grid::enableQuicksearch()
            ->setSearchForm($this->buildSearchForm())
            ->paginator(10)
            ->date('created_at', [
                'text' => 'Дата',
                'format' => Format::getFormat('datetime'),
                'order' => true,
            ])
            ->text('administrator_presentation', ['text' => 'Администратор'])
            ->text('event', ['text' => 'Событие'])
            ->text('entity_id', ['text' => 'EntityId'])
            ->text('entity_class', ['text' => 'EntityClass'])
            ->text('payload', [
                'text' => 'Payload',
                'renderer' => fn($r) => self::printJson('description', $r->payload)
            ])
            ->text('context', [
                'text' => 'Context',
                'renderer' => fn($r) => self::printJson('info', $r->context)
            ])
            ->orderBy('created_at', 'desc');
    }

    private static function printJson(string $icon, string|null $data): string
    {
        $str = json_encode(json_decode($data), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        return '<div class="btn-data-content" data-content="' . htmlspecialchars($str) . '">'
            . '<i class="icon">' . $icon . '</i>'
            . '</div>';
    }
}
