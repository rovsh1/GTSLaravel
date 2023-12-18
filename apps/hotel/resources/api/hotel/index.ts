export type HotelRoomID = number

export type HotelRoomImageResponse = {
  id: HotelRoomID
  hotel_id: number
  name: string
  rooms_number: number
  guests_number: number
  is_image_linked: boolean
}

export type HotelRoomImage = {
  id: HotelRoomID
  hotelID: number
  name: string
  roomsNumber: number
  guestsCount: number
  isImageLinked: boolean
}

export const mapHotelRoomsImageResponseToHotelRoomsImage = ({
  id,
  hotel_id: hotelID,
  name,
  rooms_number: roomsNumber,
  guests_number: guestsCount,
  is_image_linked: isImageLinked,
}: HotelRoomImageResponse): HotelRoomImage => ({
  id,
  hotelID,
  name,
  roomsNumber,
  guestsCount,
  isImageLinked,
})
