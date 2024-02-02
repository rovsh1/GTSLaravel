import { defineStore } from 'pinia'
import { z } from 'zod'

import { useGetAvailableRoomsAPI } from '~api/booking/hotel/rooms'

import { requestInitialData } from '~helpers/initial-data'

const { bookingID, hotelRooms } = requestInitialData(z.object({
  bookingID: z.number(),
  hotelRooms: z.array(
    z.object({
      id: z.number(),
      hotel_id: z.number(),
      name: z.string(),
      rooms_number: z.number(),
      guests_count: z.number(),
    }),
  ),
}))

export const useHotelRoomsStore = defineStore('booking-hotel-rooms', () => {
  const { data: availableRooms, execute: fetchAvailableRooms, isFetching: isFetchAvailableRooms } = useGetAvailableRoomsAPI({ bookingID })

  return {
    availableRooms,
    fetchAvailableRooms,
    isFetchAvailableRooms,
    hotelRooms,
  }
})
