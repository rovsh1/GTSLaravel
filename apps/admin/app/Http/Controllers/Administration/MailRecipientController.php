<?php

namespace App\Admin\Http\Controllers\Administration;

use App\Admin\Components\Factory\Prototype;
use App\Admin\Http\Controllers\Controller;
use App\Admin\Support\Facades\Breadcrumb;
use App\Admin\Support\Facades\Layout;
use App\Admin\Support\Facades\Prototypes;
use App\Admin\Support\View\Layout as LayoutContract;
use Module\Support\MailManager\Infrastructure\Model\Recipient;

class MailRecipientController extends Controller
{
    protected Prototype $prototype;

    public function __construct()
    {
        $this->prototype = Prototypes::get('mail-recipient');
    }

    public function index(): LayoutContract
    {
        Breadcrumb::prototype($this->prototype);

        return Layout::title($this->prototype->title('index'))
            ->view('mail.recipient.index', [
                'templates' => [],
                'recipients' => Recipient::query()->get()->all()
            ]);
    }
}
