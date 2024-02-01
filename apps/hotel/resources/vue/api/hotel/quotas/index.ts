import { APIDate } from '~api'
import { HotelRoomID } from '~api/hotel'
import { HotelID } from '~api/hotel/get'

export type HotelRoomQuotasUpdateBaseProps = {
  hotelID: HotelID
  roomID: HotelRoomID
  dates: APIDate[]
}
