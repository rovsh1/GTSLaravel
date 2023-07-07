import { computed, onMounted, reactive, ref } from 'vue'

import { defineStore } from 'pinia'
import { z } from 'zod'

import { showCancelFeeDialog, showNotConfirmedReasonDialog } from '~resources/views/hotel-booking/show/composables/modals'

import { updateBookingStatus, UpdateBookingStatusPayload, useGetBookingAPI } from '~api/booking'
import { HotelRoomBooking } from '~api/booking/details'
import { useBookingAvailableActionsAPI, useBookingStatusesAPI } from '~api/booking/status'
import { useHotelMarkupSettingsAPI } from '~api/hotel/markup-settings'

import { requestInitialData } from '~lib/initial-data'

const { hotelID, bookingID } = requestInitialData('view-initial-data-hotel-booking', z.object({
  hotelID: z.number(),
  bookingID: z.number(),
}))

export const useBookingStore = defineStore('booking', () => {
  const { data: booking, execute: fetchBooking } = useGetBookingAPI({ bookingID })
  const { data: markupSettings, execute: fetchMarkupSettings } = useHotelMarkupSettingsAPI({ hotelID })
  const { data: availableActions, execute: fetchAvailableActions, isFetching: isAvailableActionsFetching } = useBookingAvailableActionsAPI({ bookingID })
  const { data: statuses, execute: fetchStatuses } = useBookingStatusesAPI()

  const isEmptyGuests = computed<boolean>(() => Boolean(booking.value?.roomBookings.find((room: HotelRoomBooking) => room.guests.length === 0)))
  const isEmptyRooms = computed<boolean>(() => booking.value?.roomBookings.length === 0)
  const isStatusUpdateFetching = ref(false)

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

  onMounted(() => {
    fetchMarkupSettings()
    fetchStatuses()
    fetchBooking()
  })

  return {
    booking,
    fetchBooking,
    markupSettings,
    isEmptyGuests,
    isEmptyRooms,
    availableActions,
    fetchAvailableActions,
    isAvailableActionsFetching,
    isStatusUpdateFetching,
    statuses,
    fetchStatuses,
    changeStatus,
  }
})
