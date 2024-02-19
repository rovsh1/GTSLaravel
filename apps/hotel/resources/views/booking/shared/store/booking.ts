import { onMounted, ref } from 'vue'

import { requestInitialData } from 'gts-common/initial-data'
import { defineStore, storeToRefs } from 'pinia'
import { z } from 'zod'

import { showCancelFeeDialog, showNotConfirmedReasonDialog } from '~resources/views/booking/shared/lib/modals'
import { useBookingStatusesStore } from '~resources/views/booking/shared/store/status'
import { useBookingStatusHistoryStore } from '~resources/views/booking/shared/store/status-history'

import {
  setNoCheckInBookingStatus,
  updateBookingStatus,
  UpdateBookingStatusPayload,
  useGetBookingAPI,
} from '~api/booking/hotel'
import { UpdateBookingPrice, updateBookingPrice } from '~api/booking/hotel/price'
import { useBookingAvailableActionsAPI } from '~api/booking/status'
import { useHotelMarkupSettingsAPI } from '~api/hotel/markup-settings'

const { bookingID } = requestInitialData(z.object({
  bookingID: z.number(),
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
    if (updateStatusResponse.value?.isNotConfirmedReasonRequired) {
      const { result: isConfirmed, reason, toggleClose } = await showNotConfirmedReasonDialog()
      if (isConfirmed) {
        updateStatusPayload.notConfirmedReason = reason
        toggleClose()
        await updateBookingStatus(updateStatusPayload)
      }
    }
    if (updateStatusResponse.value?.isCancelFeeAmountRequired) {
      const { result: isConfirmed, clientCancelFeeAmount, cancelFeeAmount, toggleClose } = await showCancelFeeDialog({
        cancelFeeCurrencyLabel: booking.value?.prices.supplierPrice.currency.value || '--',
        withClientCancelFee: false,
      })
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

  const setNoCheckIn = async (penaltyValue: number | null) => {
    const response = await setNoCheckInBookingStatus({
      bookingID,
      cancelFeeAmount: penaltyValue,
    })
    if (response.data.value?.success) {
      fetchBooking()
      fetchAvailableActions()
      fetchStatusHistory()
      return true
    }
    return false
  }

  const updatePrice = async (value: UpdateBookingPrice) => {
    const response = await updateBookingPrice({
      bookingID,
      ...value,
    })
    if (response.data.value?.success) {
      fetchBooking()
      fetchAvailableActions()
      return true
    }
    return false
  }

  onMounted(() => {
    fetchMarkupSettings()
    fetchBooking()
    fetchAvailableActions()
    fetchStatusHistory()
  })

  return {
    booking,
    fetchBooking,
    markupSettings,
    availableActions,
    fetchAvailableActions,
    isAvailableActionsFetching,
    isStatusUpdateFetching,
    statuses,
    changeStatus,
    setNoCheckIn,
    updatePrice,
  }
})
