import { onMounted, ref } from 'vue'

import { defineStore, storeToRefs } from 'pinia'
import { z } from 'zod'

import {
  updateBookingStatus,
  UpdateBookingStatusPayload,
  useGetBookingAPI,
} from '~resources/api/booking/hotel'
import { showCancelFeeDialog, showNotConfirmedReasonDialog } from '~resources/views/booking/shared/lib/modals'
import { useBookingStatusesStore } from '~resources/views/booking/shared/store/status'
import { useBookingStatusHistoryStore } from '~resources/views/booking/shared/store/status-history'

import { useBookingAvailableActionsAPI } from '~api/booking/status'
import { useHotelMarkupSettingsAPI } from '~api/hotel/markup-settings'

import { requestInitialData } from '~lib/initial-data'

const { bookingID, isOtherServiceBooking, isHotelBooking } = requestInitialData(z.object({
  bookingID: z.number(),
  isOtherServiceBooking: z.boolean(),
  isHotelBooking: z.boolean(),
}))

export const useBookingStore = defineStore('booking', () => {
  const { data: booking, execute: fetchBooking } = useGetBookingAPI({ bookingID })
  const { data: markupSettings, execute: fetchMarkupSettings } = useHotelMarkupSettingsAPI()
  const { data: availableActions, execute: fetchAvailableActions, isFetching: isAvailableActionsFetching } = useBookingAvailableActionsAPI({ bookingID })
  const { fetchStatusHistory } = useBookingStatusHistoryStore()
  const bookingStatusesStore = useBookingStatusesStore()
  const { statuses } = storeToRefs(bookingStatusesStore)

  const isStatusUpdateFetching = ref(false)

  const changeStatus = async (status: number) => {
    const updateStatusPayload = { bookingID, notConfirmedReason: '' } as UpdateBookingStatusPayload
    isStatusUpdateFetching.value = true
    updateStatusPayload.status = status
    const { data: updateStatusResponse } = await updateBookingStatus(updateStatusPayload)
    if (updateStatusResponse.value?.isNotConfirmedReasonRequired && isHotelBooking) {
      const { result: isConfirmed, reason, toggleClose } = await showNotConfirmedReasonDialog()
      if (isConfirmed) {
        updateStatusPayload.notConfirmedReason = reason
        toggleClose()
        await updateBookingStatus(updateStatusPayload)
      }
    }
    if (updateStatusResponse.value?.isCancelFeeAmountRequired) {
      const { result: isConfirmed, clientCancelFeeAmount, cancelFeeAmount, toggleClose } = await showCancelFeeDialog(true)
      if (isConfirmed) {
        updateStatusPayload.cancelFeeAmount = cancelFeeAmount
        updateStatusPayload.clientCancelFeeAmount = clientCancelFeeAmount
        toggleClose()
        await updateBookingStatus(updateStatusPayload)
      }
    }
    await Promise.all([
      fetchBooking(),
      fetchAvailableActions(),
    ])
    isStatusUpdateFetching.value = false
  }

  onMounted(() => {
    fetchMarkupSettings()
    fetchBooking()
    fetchAvailableActions()
    fetchStatusHistory()
  })

  return {
    booking,
    isHotelBooking,
    isOtherServiceBooking,
    fetchBooking,
    markupSettings,
    availableActions,
    fetchAvailableActions,
    isAvailableActionsFetching,
    isStatusUpdateFetching,
    statuses,
    changeStatus,
  }
})
