import { onMounted, ref } from 'vue'

import { defineStore } from 'pinia'
import { z } from 'zod'

import { showCancelFeeDialog, showNotConfirmedReasonDialog } from '~resources/views/booking/shared/lib/modals'

import { useGetOrderGuestsAPI } from '~api/booking/order/guest'
import {
  copyOrder,
  updateManager as executeUpdateManager,
  updateNote as executeUpdateNote,
  updateOrderStatus,
  UpdateOrderStatusPayload,
  useGetOrderAPI,
} from '~api/order'
import { useBookingAvailableActionsAPI, useBookingStatusesAPI } from '~api/order/status'

import { requestInitialData } from '~lib/initial-data'

const { orderID, manager } = requestInitialData('view-initial-data-booking-order', z.object({
  orderID: z.number(),
  manager: z.object({
    id: z.number(),
  }),
}))

export const useOrderStore = defineStore('booking-order', () => {
  const { data: order, execute: fetchOrder } = useGetOrderAPI({ orderID })
  const { data: guests, execute: fetchGuests } = useGetOrderGuestsAPI({ orderId: orderID })
  const { data: availableActions, execute: fetchAvailableActions, isFetching: isAvailableActionsFetching } = useBookingAvailableActionsAPI({ orderID })
  const { data: statuses, execute: fetchStatuses } = useBookingStatusesAPI()

  const isStatusUpdateFetching = ref(false)
  const bookingManagerId = ref(manager.id)

  const changeStatus = async (status: number) => {
    const updateStatusPayload = { orderID, notConfirmedReason: '' } as UpdateOrderStatusPayload
    isStatusUpdateFetching.value = true
    updateStatusPayload.status = status
    const { data: updateStatusResponse } = await updateOrderStatus(updateStatusPayload)
    if (updateStatusResponse.value?.isNotConfirmedReasonRequired) {
      const { result: isConfirmed, reason, toggleClose } = await showNotConfirmedReasonDialog()
      if (isConfirmed) {
        updateStatusPayload.notConfirmedReason = reason
        toggleClose()
        await updateOrderStatus(updateStatusPayload)
        return
      }
    }
    if (updateStatusResponse.value?.isCancelFeeAmountRequired) {
      const { result: isConfirmed, clientCancelFeeAmount, cancelFeeAmount, toggleClose } = await showCancelFeeDialog(true)
      if (isConfirmed) {
        updateStatusPayload.cancelFeeAmount = cancelFeeAmount
        updateStatusPayload.clientCancelFeeAmount = clientCancelFeeAmount
        toggleClose()
        await updateOrderStatus(updateStatusPayload)
        return
      }
    }
    await Promise.all([
      fetchAvailableActions(),
      fetchOrder(),
    ])
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

  onMounted(() => {
    fetchOrder()
    fetchStatuses()
    fetchAvailableActions()
    fetchGuests()
  })

  return {
    order,
    guests,
    bookingManagerId,
    availableActions,
    fetchOrder,
    fetchAvailableActions,
    isAvailableActionsFetching,
    isStatusUpdateFetching,
    statuses,
    fetchStatuses,
    changeStatus,
    copy,
    updateNote,
    updateManager,
  }
})
