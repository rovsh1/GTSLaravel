import { APIDate } from '~resources/lib/api'
import { HotelID } from '~resources/lib/api/hotel/get'
import { HotelRoomID } from '~resources/lib/api/hotel/room'

export type HotelRoomQuotasUpdateBaseProps = {
  hotelID: HotelID
  roomID: HotelRoomID
  dates: APIDate[]
}
