import { nextTick, onMounted, Ref, ref } from 'vue'

import { defineStore, storeToRefs } from 'pinia'
import { z } from 'zod'

import { useGetOrderBookingsAPI } from '~resources/api/order/booking'
import { showCancelFeeDialog } from '~resources/views/booking/shared/lib/modals'
import { useOrderStatusesStore } from '~resources/views/booking-order/show/store/status'

import {
  copyOrder,
  updateManager as executeUpdateManager,
  updateNote as executeUpdateNote,
  updateOrderStatus,
  UpdateOrderStatusPayload,
  useGetOrderAPI,
} from '~api/order'
import { useGetOrderGuestsAPI } from '~api/order/guest'
import { useOrderAvailableActionsAPI } from '~api/order/status'
import { createOrderVoucher, OrderVoucher, sendOrderVoucher } from '~api/order/voucher'

import { showConfirmDialog } from '~lib/confirm-dialog'
import { requestInitialData } from '~lib/initial-data'

const { orderID, manager } = requestInitialData(z.object({
  orderID: z.number(),
  manager: z.object({
    id: z.number(),
  }),
}))

export const useOrderStore = defineStore('booking-order', () => {
  const { data: order, execute: fetchOrder } = useGetOrderAPI({ orderID })
  const { data: guests, execute: fetchGuests } = useGetOrderGuestsAPI({ orderId: orderID })
  const { data: bookings, execute: fetchBookings } = useGetOrderBookingsAPI({ orderId: orderID })
  const { data: availableActions, execute: fetchAvailableActions, isFetching: isAvailableActionsFetching } = useOrderAvailableActionsAPI({ orderID })
  const orderStatusesStore = useOrderStatusesStore()
  const { statuses } = storeToRefs(orderStatusesStore)

  const isStatusUpdateFetching = ref(false)
  const isVoucherFetching = ref(false)
  const bookingManagerId = ref(manager.id)

  const refreshOrder = async () => {
    await Promise.all([
      fetchAvailableActions(),
      fetchOrder(),
      fetchGuests(),
      fetchBookings(),
    ])
  }

  const changeStatus = async (status: number) => {
    const updateStatusPayload = { orderID } as UpdateOrderStatusPayload
    isStatusUpdateFetching.value = true
    updateStatusPayload.status = status
    const { data: updateStatusResponse } = await updateOrderStatus(updateStatusPayload)
    if (updateStatusResponse.value?.isRefundFeeAmountRequired) {
      const { result: isConfirmed, cancelFeeAmount, toggleClose } = await showCancelFeeDialog()
      if (isConfirmed) {
        updateStatusPayload.refundFee = cancelFeeAmount
        toggleClose()
        await updateOrderStatus(updateStatusPayload)
      }
    }
    await refreshOrder()
    isStatusUpdateFetching.value = false
  }

  const copy = async () => {
    await copyOrder({ orderID })
  }

  const updateNote = async (note?: string) => {
    await executeUpdateNote({ orderID, note })
    fetchOrder()
  }

  const updateManager = async (managerId: number) => {
    await executeUpdateManager({ orderID, managerId })
    bookingManagerId.value = Number(managerId)
  }

  const createVoucher = async (): Promise<Ref<OrderVoucher>> => {
    isVoucherFetching.value = true
    const { data: voucher } = await createOrderVoucher({ orderID })
    nextTick(refreshOrder)
    isVoucherFetching.value = false
    return voucher as Ref<OrderVoucher>
  }

  const sendVoucher = async () => {
    const { result: isConfirmed, toggleClose } = await showConfirmDialog('Отправить ваучер?')
    if (isConfirmed) {
      isVoucherFetching.value = true
      nextTick(toggleClose)
      await sendOrderVoucher({ orderID })
    }
    refreshOrder()
    isVoucherFetching.value = false
  }

  onMounted(() => {
    fetchOrder()
    fetchAvailableActions()
    fetchGuests()
    fetchBookings()
  })

  return {
    order,
    bookings,
    guests,
    bookingManagerId,
    availableActions,
    fetchBookings,
    fetchGuests,
    fetchOrder,
    fetchAvailableActions,
    refreshOrder,
    isAvailableActionsFetching,
    isStatusUpdateFetching,
    statuses,
    changeStatus,
    copy,
    updateNote,
    updateManager,
    isVoucherFetching,
    createVoucher,
    sendVoucher,
  }
})
