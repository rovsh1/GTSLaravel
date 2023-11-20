<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\UseCase\Order;

use Module\Booking\Moderation\Application\Exception\OrderHasBookingInProgressException;
use Module\Booking\Moderation\Domain\Order\Exception\OrderHasBookingInProgress;
use Module\Booking\Moderation\Domain\Order\Service\StatusUpdater;
use Module\Booking\Shared\Domain\Order\Order;
use Module\Booking\Shared\Domain\Order\Repository\OrderRepositoryInterface;
use Module\Booking\Shared\Domain\Order\ValueObject\OrderId;
use Module\Shared\Enum\Order\OrderStatusEnum;
use Module\Shared\Exception\ApplicationException;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class UpdateStatus implements UseCaseInterface
{
    public function __construct(
        private readonly OrderRepositoryInterface $repository,
        private readonly StatusUpdater $statusUpdater,
    ) {}

    //@todo инвоисы
    //Отменить/Удалить инвоис - и заказы вовзращаются в статус "В работе"

    //Отмена заказа без оплаты - отмена инвоиса

    //Отмены - через доменные ивенты в модуле Invoicing

    //3 колонки: сумма, распределено, не распределено + общая сумма всех нераспределенных

    /**
     * Статусы заказа для посадки оплаты
     *
     * self::INVOICED,
     * self::PARTIAL_PAID,
     * self::REFUND_FEE,
     */

    //@todo оплаты поставщикам

    public function execute(int $orderId, int $statusId): void
    {
        try {
            $order = $this->repository->findOrFail(new OrderId($orderId));
            $statusEnum = OrderStatusEnum::from($statusId);
            $this->updateStatus($order, $statusEnum);
            $this->repository->store($order);
        } catch (OrderHasBookingInProgress $e) {
            throw new OrderHasBookingInProgressException($e);
        } catch (\Throwable $e) {
            throw new ApplicationException($e->getMessage(), $e->getCode(), $e);
        }
    }

    private function updateStatus(Order $order, OrderStatusEnum $status): void
    {
        switch ($status) {
            case OrderStatusEnum::IN_PROGRESS:
                $this->statusUpdater->toInProgress($order);
                break;
            case OrderStatusEnum::WAITING_INVOICE:
                $this->statusUpdater->toWaitingInvoice($order);
                break;
            case OrderStatusEnum::INVOICED:
                $this->statusUpdater->toInvoiced($order);
                break;
            case OrderStatusEnum::PARTIAL_PAID:
                $this->statusUpdater->toPartialPaid($order);
                break;
            case OrderStatusEnum::PAID:
                $this->statusUpdater->toPaid($order);
                break;
            case OrderStatusEnum::CANCELLED:
                $this->statusUpdater->cancel($order);
                break;
            case OrderStatusEnum::REFUND_FEE:
                $this->statusUpdater->toRefundFee($order);
                break;
            case OrderStatusEnum::REFUND_NO_FEE:
                $this->statusUpdater->toRefundNoFee($order);
                break;
        }
    }
}
