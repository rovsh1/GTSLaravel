import { onMounted, reactive, ref } from 'vue'

import { defineStore } from 'pinia'
import { z } from 'zod'

import { showCancelFeeDialog } from '~resources/views/booking/lib/modals'

import {
  copyBooking,
  updateBookingDetails as executeUpdateDetails,
  updateBookingStatus,
  UpdateBookingStatusPayload,
  updateManager as executeUpdateManager,
  updateNote as executeUpdateNote,
  useGetBookingAPI,
} from '~api/booking/service'
import { useBookingAvailableActionsAPI, useBookingStatusesAPI } from '~api/booking/service/status'

import { requestInitialData } from '~lib/initial-data'

const { bookingID, manager } = requestInitialData('view-initial-data-service-booking', z.object({
  bookingID: z.number(),
  manager: z.object({
    id: z.number(),
  }),
}))

export const useBookingStore = defineStore('booking', () => {
  const { data: booking, execute: fetchBooking } = useGetBookingAPI({ bookingID })
  const { data: availableActions, execute: fetchAvailableActions, isFetching: isAvailableActionsFetching } = useBookingAvailableActionsAPI({ bookingID })
  const { data: statuses, execute: fetchStatuses } = useBookingStatusesAPI()

  const isStatusUpdateFetching = ref(false)
  const bookingManagerId = ref(manager.id)

  const updateStatusPayload = reactive<UpdateBookingStatusPayload>({ bookingID, notConfirmedReason: '' } as UpdateBookingStatusPayload)
  const changeStatus = async (status: number) => {
    isStatusUpdateFetching.value = true
    updateStatusPayload.status = status
    const { data: updateStatusResponse } = await updateBookingStatus(updateStatusPayload)
    if (updateStatusResponse.value?.isCancelFeeAmountRequired) {
      const { result: isConfirmed, clientCancelFeeAmount, cancelFeeAmount, toggleClose } = await showCancelFeeDialog(true)
      if (isConfirmed) {
        updateStatusPayload.cancelFeeAmount = cancelFeeAmount
        updateStatusPayload.clientCancelFeeAmount = clientCancelFeeAmount
        toggleClose()
        await changeStatus(status)
        updateStatusPayload.cancelFeeAmount = undefined
        updateStatusPayload.clientCancelFeeAmount = undefined
        return
      }
    }
    await Promise.all([
      fetchBooking(),
      fetchAvailableActions(),
    ])
    isStatusUpdateFetching.value = false
  }

  const copy = async () => {
    await copyBooking({ bookingID })
  }

  const updateNote = async (note?: string) => {
    await executeUpdateNote({ bookingID, note })
    fetchBooking()
  }

  const updateManager = async (managerId: number) => {
    await executeUpdateManager({ bookingID, managerId })
    bookingManagerId.value = Number(managerId)
  }

  const updateDetails = async (field: string, value: any) => {
    await executeUpdateDetails({ bookingID, field, value })
    fetchBooking()
  }

  onMounted(() => {
    fetchStatuses()
    fetchBooking()
    fetchAvailableActions()
  })

  return {
    booking,
    bookingManagerId,
    fetchBooking,
    availableActions,
    fetchAvailableActions,
    isAvailableActionsFetching,
    isStatusUpdateFetching,
    statuses,
    fetchStatuses,
    changeStatus,
    copy,
    updateNote,
    updateManager,
    updateDetails,
  }
})
