import { computed, onMounted, ref } from 'vue'

import { defineStore } from 'pinia'
import { z } from 'zod'

import { showCancelFeeDialog, showNotConfirmedReasonDialog } from '~resources/views/booking/shared/lib/modals'

import { HotelRoomBooking } from '~api/booking/hotel/details'
import {
  copyBooking,
  updateBookingDetails as executeUpdateDetails,
  updateBookingStatus,
  UpdateBookingStatusPayload,
  updateManager as executeUpdateManager,
  updateNote as executeUpdateNote,
  useGetBookingAPI,
} from '~api/booking/service'
import { UpdateBookingPrice, updateBookingPrice, useRecalculateBookingPriceAPI } from '~api/booking/service/price'
import { useBookingAvailableActionsAPI, useBookingStatusesAPI } from '~api/booking/status'
import { useHotelMarkupSettingsAPI } from '~api/hotel/markup-settings'

import { isInitialDataExists, requestInitialData, ViewInitialDataKey } from '~lib/initial-data'

let isHotelBooking = true
let initialDataKey: ViewInitialDataKey = 'view-initial-data-hotel-booking'
if (isInitialDataExists('view-initial-data-service-booking')) {
  isHotelBooking = false
  initialDataKey = 'view-initial-data-service-booking'
}

const { bookingID, manager, hotelID } = requestInitialData(initialDataKey, z.object({
  hotelID: z.number().optional(),
  bookingID: z.number(),
  manager: z.object({
    id: z.number(),
  }),
}))

export const useBookingStore = defineStore('booking', () => {
  const { data: booking, execute: fetchBooking } = useGetBookingAPI({ bookingID })
  const { data: markupSettings, execute: fetchMarkupSettings } = useHotelMarkupSettingsAPI(hotelID ? { hotelID } : null)
  const { data: availableActions, execute: fetchAvailableActions, isFetching: isAvailableActionsFetching } = useBookingAvailableActionsAPI({ bookingID })
  const { data: statuses, execute: fetchStatuses } = useBookingStatusesAPI()
  const { isFetching: isRecalculatePrice, execute: recalculateBookingPrice } = useRecalculateBookingPriceAPI({ bookingID })

  const isStatusUpdateFetching = ref(false)
  const bookingManagerId = ref(manager.id)

  const isRecalculateBookingPrice = computed<boolean>(() => isRecalculatePrice.value)

  const existRooms = computed<boolean>(() => Boolean(booking.value?.details?.roomBookings))
  const isEmptyRoomsGuests = computed<boolean>(() => Boolean(booking.value?.details?.roomBookings.find((room: HotelRoomBooking) => room.guestIds.length === 0)))
  const isEmptyRooms = computed<boolean>(() => booking.value?.details?.roomBookings.length === 0)

  const existCars = computed<boolean>(() => Boolean(booking.value?.details?.carBids))
  const isEmptyCars = computed<boolean>(() => Boolean(booking.value?.details?.carBids?.length === 0))

  const existGuests = computed<boolean>(() => Boolean(booking.value?.details?.guestIds))
  const isEmptyGuests = computed<boolean>(() => Boolean(booking.value?.details?.guestIds.length === 0))

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
        return
      }
    }
    if (updateStatusResponse.value?.isCancelFeeAmountRequired) {
      const { result: isConfirmed, clientCancelFeeAmount, cancelFeeAmount, toggleClose } = await showCancelFeeDialog(true)
      if (isConfirmed) {
        updateStatusPayload.cancelFeeAmount = cancelFeeAmount
        updateStatusPayload.clientCancelFeeAmount = clientCancelFeeAmount
        toggleClose()
        await updateBookingStatus(updateStatusPayload)
        return
      }
    }
    await Promise.all([
      fetchBooking(),
      fetchAvailableActions(),
    ])
    isStatusUpdateFetching.value = false
  }

  const recalculatePrice = async () => {
    await recalculateBookingPrice()
    fetchBooking()
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

  const updatePrice = async (value: UpdateBookingPrice) => {
    await updateBookingPrice({
      bookingID,
      ...value,
    })
    fetchBooking()
  }

  onMounted(() => {
    fetchMarkupSettings()
    fetchStatuses()
    fetchBooking()
    fetchAvailableActions()
  })

  return {
    booking,
    isHotelBooking,
    bookingManagerId,
    fetchBooking,
    markupSettings,
    availableActions,
    fetchAvailableActions,
    isAvailableActionsFetching,
    isStatusUpdateFetching,
    statuses,
    fetchStatuses,
    changeStatus,
    existCars,
    isEmptyCars,
    existGuests,
    isEmptyGuests,
    existRooms,
    isEmptyRoomsGuests,
    isEmptyRooms,
    isRecalculateBookingPrice,
    recalculatePrice,
    updatePrice,
    copy,
    updateNote,
    updateManager,
    updateDetails,
  }
})
