<?php

namespace App\Admin\Http\Controllers\Data;

use App\Admin\Components\Factory\Prototype;
use App\Admin\Http\Controllers\Controller;
use App\Admin\Models\Reference\Dictionary;
use App\Admin\Support\Facades\Layout;
use App\Admin\Support\Facades\Prototypes;
use App\Admin\Support\View\Layout as LayoutContract;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LocaleDictionaryController extends Controller
{
    protected Prototype $prototype;

    public function __construct()
    {
        $this->prototype = Prototypes::get('locale-dictionary');
    }

    public function index(): LayoutContract
    {
        return Layout::title($this->prototype->title('index'))
            ->view('locale-dictionary.index', [

            ]);
    }

    public function update(Request $request): JsonResponse
    {
        $data = $request->post();
        $dictionary = Dictionary::findByKey($data['key']);
        $dictionary->storeValues($data);

        return response()->json([]);
    }

    public function search(Request $request): JsonResponse
    {
        $query = Dictionary::withValues();

        if (($term = $request->term)) {
            $query->whereTerm($term);
        } else {
            $query->whereHasEmptyValue();
        }

        return response()->json($query->get());
    }
}
