<?php

namespace Module\Generic\Notification\Infrastructure\Service;

use App\Admin\Models\Administrator\Administrator;
use App\Admin\Models\Hotel\Contact as HotelContact;
use App\Admin\Models\Hotel\User as HotelUser;
use Module\Generic\Notification\Domain\MailSettings\Dto\RecipientDto;
use Module\Generic\Notification\Domain\MailSettings\Dto\RecipientListDto;

class MailRecipientBuilder
{
    /**
     * @var RecipientDto[]
     */
    private array $recipients = [];

    public function build(): RecipientListDto
    {
        return new RecipientListDto($this->recipients);
    }

    public function addEmail(string $email, ?string $presentation): static
    {
        return $this->_add(new RecipientDto($email, $presentation));
    }

    public function addAdministrator(int $administratorId): static
    {
        $model = Administrator::find($administratorId);
        if (!$model) {
            throw new \Exception("Administrator [$administratorId] not found");
        }

        if (empty($model->email)) {
            return $this;
        }

        return $this->_add(new RecipientDto($model->email, $model->presentation));
    }

    public function addAdministratorGroup(int $groupId): static
    {
        return $this->mapQuery(
            Administrator::whereGroup($groupId)->whereNotNull('email'),
            fn($r) => new RecipientDto($r->email, $r->presentation)
        );
    }

    public function addClientContacts(int $clientId): static
    {
        return $this->mapQuery(
            ClientContact::whereGroup($clientId)->whereIsEmail(),
            fn($r) => new RecipientDto($r->value, $r->presentation)
        );
    }

    public function addHotelContacts(int $hotelId): static
    {
        return $this->mapQuery(
            HotelContact::where('hotel_id', $hotelId)->whereIsEmail(),
            fn($r) => new RecipientDto($r->email, $r->presentation)
        );
    }

    public function addHotelUsers(int $hotelId): static
    {
        return $this->mapQuery(
            HotelUser::where('hotel_id', $hotelId)->whereNotNull('email'),
            fn($r) => new RecipientDto($r->email, $r->presentation)
        );
    }

    private function mapQuery($query, callable $mapper): static
    {
        foreach ($query->get()->map($mapper) as $recipient) {
            $this->_add($recipient);
        }

        return $this;
    }

    private function _add(RecipientDto $recipient): static
    {
        foreach ($this->recipients as $r) {
            if ($r->email === $recipient->email) {
                return $this;
            }
        }
        $this->recipients[] = $recipient;

        return $this;
    }
}
