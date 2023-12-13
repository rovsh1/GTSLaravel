import { computed, onMounted, ref } from 'vue'

import { defineStore, storeToRefs } from 'pinia'
import { z } from 'zod'

import { showCancelFeeDialog, showNotConfirmedReasonDialog } from '~resources/views/booking/shared/lib/modals'
import { useBookingStatusesStore } from '~resources/views/booking/shared/store/status'
import { useBookingStatusHistoryStore } from '~resources/views/booking/shared/store/status-history'

import { HotelRoomBooking } from '~api/booking/hotel/details'
import {
  CarBid,
  copyBooking,
  updateBookingDetails as executeUpdateDetails,
  updateBookingStatus,
  UpdateBookingStatusPayload,
  updateManager as executeUpdateManager,
  updateNote as executeUpdateNote,
  useGetBookingAPI,
} from '~api/booking/service'
import { UpdateBookingPrice, updateBookingPrice, useRecalculateBookingPriceAPI } from '~api/booking/service/price'
import { useBookingAvailableActionsAPI } from '~api/booking/status'
import { useHotelMarkupSettingsAPI } from '~api/hotel/markup-settings'

import { requestInitialData } from '~lib/initial-data'

const { bookingID, manager, hotelID, isOtherServiceBooking, isHotelBooking } = requestInitialData(z.object({
  hotelID: z.number().optional(),
  bookingID: z.number(),
  isOtherServiceBooking: z.boolean(),
  isHotelBooking: z.boolean(),
  manager: z.object({
    id: z.number(),
  }),
}))

export const useBookingStore = defineStore('booking', () => {
  const { data: booking, execute: fetchBooking } = useGetBookingAPI({ bookingID })
  const { data: markupSettings, execute: fetchMarkupSettings } = useHotelMarkupSettingsAPI(hotelID ? { hotelID } : null)
  const { data: availableActions, execute: fetchAvailableActions, isFetching: isAvailableActionsFetching } = useBookingAvailableActionsAPI({ bookingID })
  const { isFetching: isRecalculateBookingPrice, execute: recalculateBookingPrice } = useRecalculateBookingPriceAPI({ bookingID })
  const { fetchStatusHistory } = useBookingStatusHistoryStore()
  const bookingStatusesStore = useBookingStatusesStore()
  const { fetchStatuses } = bookingStatusesStore
  const { statuses } = storeToRefs(bookingStatusesStore)

  const isStatusUpdateFetching = ref(false)
  const bookingManagerId = ref(manager.id)

  const existRooms = computed<boolean>(() => Boolean(booking.value?.details?.roomBookings))
  const isEmptyRoomsGuests = computed<boolean>(() => Boolean(booking.value?.details?.roomBookings.find((room: HotelRoomBooking) => room.guestIds.length === 0)))
  const isEmptyRooms = computed<boolean>(() => booking.value?.details?.roomBookings.length === 0)

  const existCars = computed<boolean>(() => Boolean(booking.value?.details?.carBids))
  const isEmptyCarsGuests = computed<boolean>(() => Boolean(booking.value?.details?.carBids.find((car: CarBid) => car.guestIds.length === 0)))
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
    fetchBooking()
    fetchAvailableActions()
    fetchStatusHistory()
  })

  return {
    booking,
    isHotelBooking,
    isOtherServiceBooking,
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
    isEmptyCarsGuests,
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
