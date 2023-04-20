import { File } from '~resources/lib/models'

export interface HotelImage {
  id: number
  title: string | null
  order: number
  hotel_id: number
  file: File
}
