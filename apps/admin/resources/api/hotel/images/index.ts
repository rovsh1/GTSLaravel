import { HotelID } from '~api/hotel/get'

export type HotelImageID = number

export type FileResponse = {
  guid: string
  name: string
  url: string
  entity_id: number
}

export type HotelImageResponse = {
  id: HotelImageID
  hotel_id: HotelID
  title: string | null
  order: number
  file: FileResponse
}

export type HotelImage = {
  id: HotelImageID
  hotelID: HotelID
  title: string | null
  order: number
  file: FileResponse
}

export const mapHotelImageResponseToImageResponse = ({
  id,
  hotel_id: hotelID,
  title,
  order,
  file,
}: HotelImageResponse): HotelImage => ({
  id,
  hotelID,
  title,
  order,
  file,
})
