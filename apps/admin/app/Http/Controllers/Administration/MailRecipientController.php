<?php

namespace App\Admin\Http\Controllers\Administration;

use App\Admin\Components\Factory\Prototype;
use App\Admin\Http\Controllers\Controller;
use App\Admin\Support\Facades\Breadcrumb;
use App\Admin\Support\Facades\Layout;
use App\Admin\Support\Facades\Prototypes;
use App\Admin\Support\View\Layout as LayoutContract;
use App\Shared\Http\Responses\AjaxSuccessResponse;
use Illuminate\Http\Request;
use Module\Generic\Notification\Application\Dto\MailRecipientDto;
use Module\Generic\Notification\Application\UseCase\MailSettings\GatMailSettings;
use Module\Generic\Notification\Application\UseCase\MailSettings\UpdateRecipients;

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
        $settings = app(GatMailSettings::class)->execute();

        return Layout::title($this->prototype->title('index'))
            ->addJsVariable('mail-settings', $settings)
            ->view('mail.recipient.index');
    }

    public function update(Request $request): AjaxSuccessResponse
    {
        $recipients = array_map(fn($r) => new MailRecipientDto(
            type: $r->type,
            payload: $r->payload
        ), $request->recipients);

        app(UpdateRecipients::class)->execute($request->id, $recipients);

        return new AjaxSuccessResponse();
    }
}
