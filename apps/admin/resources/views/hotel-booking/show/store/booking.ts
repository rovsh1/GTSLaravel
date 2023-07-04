import { computed, onMounted, reactive, ref } from 'vue'

import { defineStore } from 'pinia'
import { z } from 'zod'

import { showCancelFeeDialog, showNotConfirmedReasonDialog } from '~resources/views/hotel-booking/show/modals'

import { updateBookingStatus, UpdateBookingStatusPayload, updateExternalNumber as updateExternalNumberRequest, useGetBookingAPI } from '~api/booking'
import { ExternalNumber, ExternalNumberTypeEnum, HotelRoomBooking } from '~api/booking/details'
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
  const isUpdateExternalNumberFetching = ref(false)
  const isExternalNumberValid = ref(true)

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

  const validateExternalNumber = (numberType: ExternalNumberTypeEnum, number: string | null): boolean => {
    // @todo валидация перед переходом на статус "Подтверждена" для админки отелей.
    const isHotelNumberType = numberType === ExternalNumberTypeEnum.HotelBookingNumber
    const isEmptyNumber = (!number || number?.trim().length === 0)
    if (isHotelNumberType && isEmptyNumber) {
      isExternalNumberValid.value = false
      return false
    }
    isExternalNumberValid.value = true
    return true
  }

  const updateExternalNumber = async (externalNumber: ExternalNumber): Promise<boolean> => {
    if (!validateExternalNumber(externalNumber.type, externalNumber.number)) {
      return false
    }
    isUpdateExternalNumberFetching.value = true
    await updateExternalNumberRequest({ bookingID, ...externalNumber })
    await fetchBooking()
    isUpdateExternalNumberFetching.value = false
    return true
  }

  onMounted(() => {
    fetchMarkupSettings()
    fetchStatuses()
    fetchBooking()
  })

  return {
    booking,
    markupSettings,
    fetchBooking,
    isEmptyGuests,
    isEmptyRooms,
    availableActions,
    fetchAvailableActions,
    isAvailableActionsFetching,
    isStatusUpdateFetching,
    isUpdateExternalNumberFetching,
    statuses,
    fetchStatuses,
    changeStatus,
    updateExternalNumber,
    isExternalNumberValid,
    validateExternalNumber,
  }
})
