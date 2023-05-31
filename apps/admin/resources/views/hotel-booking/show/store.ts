import { computed } from 'vue'

import { defineStore } from 'pinia'
import { z } from 'zod'

import { HotelBookingDetailsRoom, useBookingHotelDetailsAPI } from '~api/booking/details'
import { useHotelMarkupSettingsAPI } from '~api/hotel/markup-settings'
import { HotelRoomResponse } from '~api/hotel/room'

import { requestInitialData } from '~lib/initial-data'

const { hotelRooms, hotelID, bookingID } = requestInitialData('view-initial-data-hotel-booking', z.object({
  hotelID: z.number(),
  bookingID: z.number(),
  hotelRooms: z.array(
    z.object({
      id: z.number(),
      hotel_id: z.number(),
      name: z.string(),
      custom_name: z.string(),
      rooms_number: z.number(),
      guests_number: z.number(),
    }),
  ),
}))

export const useBookingStore = defineStore('booking', () => {
  const { data: bookingDetails, execute: fetchDetails } = useBookingHotelDetailsAPI({ bookingID })
  const { data: markupSettings, execute: fetchMarkupSettings } = useHotelMarkupSettingsAPI({ hotelID })

  const hotelRoomGuests: Record<number, number> = {}
  hotelRooms.forEach((hotelRoom: HotelRoomResponse) => {
    hotelRoomGuests[hotelRoom.id] = hotelRoom.guests_number
  })

  const isEmptyGuests = computed<boolean>(() => Boolean(bookingDetails.value?.rooms.find((room: HotelBookingDetailsRoom) => {
    const roomGuests = hotelRoomGuests[room.id]
    return room.guests.length !== roomGuests
  })))
  const isEmptyRooms = computed<boolean>(() => bookingDetails.value?.rooms.length === 0)

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
