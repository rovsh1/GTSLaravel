import { APIDate } from '~api'
import { HotelID } from '~api/hotel/get'
import { HotelRoomID } from '~api/hotel/room'

export type HotelRoomQuotasUpdateBaseProps = {
  hotelID: HotelID
  roomID: HotelRoomID
  dates: APIDate[]
}
