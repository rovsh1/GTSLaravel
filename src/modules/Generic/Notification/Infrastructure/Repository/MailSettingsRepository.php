<?php

namespace Module\Generic\Notification\Infrastructure\Repository;

use Illuminate\Support\Facades\DB;
use Module\Generic\Notification\Domain\MailSettings\Factory\RecipientFactory;
use Module\Generic\Notification\Domain\MailSettings\MailSettings;
use Module\Generic\Notification\Domain\MailSettings\Repository\MailSettingsRepositoryInterface;
use Module\Generic\Notification\Domain\Shared\Enum\NotificationTypeEnum;

class MailSettingsRepository implements MailSettingsRepositoryInterface
{
    public function find(NotificationTypeEnum $id): MailSettings
    {
        $recipients = DB::table('s_mail_recipients')
            ->where('notification_type', self::typeToId($id))
            ->get()
            ->all();

        $recipientFactory = new RecipientFactory();

        return new MailSettings(
            id: $id,
            recipients: array_map(
                fn($r) => $recipientFactory->fromPayload(json_decode($r->recipient, true)),
                $recipients
            )
        );
    }

    public function store(MailSettings $rule): void
    {
        $type = self::typeToId($rule->id());
        $insert = [];
        foreach ($rule->recipients() as $recipient) {
            $insert[] = [
                'notification_type' => $type,
                'recipient' => json_encode([
                    'type' => $recipient->type(),
                    ...$recipient->toArray()
                ])
            ];
        }

        DB::transaction(function () use ($type, $insert) {
            DB::table('s_mail_recipients')
                ->where('notification_type', $type)
                ->delete();

            DB::table('s_notification_recipients')
                ->insert($insert);
        });
    }

    private static function typeToId(NotificationTypeEnum $type): string
    {
        return strtolower($type->name);
    }
}