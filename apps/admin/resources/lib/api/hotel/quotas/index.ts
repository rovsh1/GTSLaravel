import { APIDate } from '~lib/api'
import { HotelID } from '~lib/api/hotel/get'
import { HotelRoomID } from '~lib/api/hotel/room'

export type HotelRoomQuotasUpdateBaseProps = {
  hotelID: HotelID
  roomID: HotelRoomID
  dates: APIDate[]
}
