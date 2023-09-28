import { computed, onMounted, reactive, ref } from 'vue'

import { defineStore } from 'pinia'
import { z } from 'zod'

import { showCancelFeeDialog, showNotConfirmedReasonDialog } from '~resources/views/hotel-booking/show/lib/modals'

import {
  copyBooking,
  updateBookingStatus,
  UpdateBookingStatusPayload,
  updateManager as executeUpdateManager,
  updateNote as executeUpdateNote,
  useGetBookingAPI,
} from '~api/booking/airport'
import { useBookingAvailableActionsAPI, useBookingStatusesAPI } from '~api/booking/airport/status'

import { requestInitialData } from '~lib/initial-data'

const { bookingID, manager } = requestInitialData('view-initial-data-airport-booking', z.object({
  bookingID: z.number(),
  manager: z.object({
    id: z.number(),
  }),
}))

export const useBookingStore = defineStore('booking', () => {
  const { data: booking, execute: fetchBooking } = useGetBookingAPI({ bookingID })
  const { data: availableActions, execute: fetchAvailableActions, isFetching: isAvailableActionsFetching } = useBookingAvailableActionsAPI({ bookingID })
  const { data: statuses, execute: fetchStatuses } = useBookingStatusesAPI()

  const isEmptyGuests = computed<boolean>(() => (!(booking.value && booking.value?.guestIds.length > 0)))

  const isStatusUpdateFetching = ref(false)
  const bookingManagerId = ref(manager.id)

  const updateStatusPayload = reactive<UpdateBookingStatusPayload>({ bookingID } as UpdateBookingStatusPayload)
  const changeStatus = async (status: number) => {
    isStatusUpdateFetching.value = true
    updateStatusPayload.status = status
    const { data: updateStatusResponse } = await updateBookingStatus(updateStatusPayload)
    if (updateStatusResponse.value?.isNotConfirmedReasonRequired) {
      const { result: isConfirmed, reason, toggleClose } = await showNotConfirmedReasonDialog()
      if (isConfirmed) {
        updateStatusPayload.notConfirmedReason = reason
        toggleClose()
        await changeStatus(status)
        updateStatusPayload.notConfirmedReason = undefined
        return
      }
    }
    if (updateStatusResponse.value?.isCancelFeeAmountRequired) {
      const { result: isConfirmed, cancelFeeAmount, toggleClose } = await showCancelFeeDialog()
      if (isConfirmed) {
        updateStatusPayload.cancelFeeAmount = cancelFeeAmount
        toggleClose()
        await changeStatus(status)
        updateStatusPayload.cancelFeeAmount = undefined
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

  onMounted(() => {
    fetchStatuses()
    fetchBooking()
    fetchAvailableActions()
  })

  return {
    booking,
    bookingManagerId,
    fetchBooking,
    isEmptyGuests,
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
  }
})