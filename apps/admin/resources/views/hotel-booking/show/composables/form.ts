import { MarkupCondition } from '~api/hotel/markup-settings'

export interface RoomFormData {
  bookingID: number
  id?: number
  status?: number
  residentType?: number
  discount?: number
  rateId?: number
  note?: string
  earlyCheckIn?: MarkupCondition
  lateCheckOut?: MarkupCondition
  roomBookingId?: number
}

export interface GuestFormData {
  bookingID: number
  roomBookingId?: number
  guestIndex?: number
  countryId?: number
  fullName?: string
  gender?: number
  isAdult?: boolean
  age?: number | null
}
