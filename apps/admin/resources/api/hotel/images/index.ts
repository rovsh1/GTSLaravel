import { HotelID } from '~api/hotel/get'

export type HotelImageID = number
export type HotelImageIndex = number

export type FileResponse = {
  guid: string
  name: string
  url: string
  entity_id?: number
}

export type HotelImageResponse = {
  id: HotelImageID
  hotel_id: HotelID
  title: string | null
  index: HotelImageIndex
  file: FileResponse
  isMain?: boolean
}

export type HotelImage = {
  id: HotelImageID
  hotelID: HotelID
  title: string | null
  index: HotelImageIndex
  file: FileResponse
  isMain?: boolean
}

export const mapHotelImageResponseToImageResponse = ({
  id,
  hotel_id: hotelID,
  title,
  index,
  file,
  isMain,
}: HotelImageResponse): HotelImage => ({
  id,
  hotelID,
  title,
  index,
  file,
  isMain,
})
