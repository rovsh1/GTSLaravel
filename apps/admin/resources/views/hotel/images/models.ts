import { File } from '~resources/lib/models'

export interface HotelImage {
  id: number
  title: string | null
  order: number
  hotel_id: number
  file: File
}

export interface RoomImage extends HotelImage {
  room_id: number
  image_id: number
}
