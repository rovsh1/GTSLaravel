import { computed } from 'vue'

import { defineStore } from 'pinia'
import { z } from 'zod'

import { HotelRoomBooking, useBookingHotelDetailsAPI } from '~api/booking/details'
import { useHotelMarkupSettingsAPI } from '~api/hotel/markup-settings'

import { requestInitialData } from '~lib/initial-data'

const { hotelID, bookingID } = requestInitialData('view-initial-data-hotel-booking', z.object({
  hotelID: z.number(),
  bookingID: z.number(),
}))

export const useBookingStore = defineStore('booking', () => {
  const { data: bookingDetails, execute: fetchDetails } = useBookingHotelDetailsAPI({ bookingID })
  const { data: markupSettings, execute: fetchMarkupSettings } = useHotelMarkupSettingsAPI({ hotelID })

  const isEmptyGuests = computed<boolean>(() => Boolean(bookingDetails.value?.roomBookings.find((room: HotelRoomBooking) => room.guests.length === 0)))
  const isEmptyRooms = computed<boolean>(() => bookingDetails.value?.roomBookings.length === 0)

  const fetchBookingDetails = async (): Promise<void> => {
    await fetchDetails()
  }

  fetchMarkupSettings()

  return {
    bookingDetails,
    markupSettings,
    fetchBookingDetails,
    isEmptyGuests,
    isEmptyRooms,
  }
})
