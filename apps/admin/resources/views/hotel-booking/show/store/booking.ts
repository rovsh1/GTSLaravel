import { computed } from 'vue'

import { defineStore } from 'pinia'
import { z } from 'zod'

import { useGetBookingAPI } from '~api/booking'
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

  fetchMarkupSettings()
  fetchStatuses()
  fetchBooking()

  return {
    booking,
    markupSettings,
    fetchBooking,
    isEmptyGuests,
    isEmptyRooms,
    availableActions,
    fetchAvailableActions,
    isAvailableActionsFetching,
    statuses,
    fetchStatuses,
  }
})
