import { MarkupCondition } from '~api/hotel/markup-settings'

export interface TransferFormData {
  bookingID: number
  id?: number
}

export interface RoomFormData {
  bookingID: number
  id?: number
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
  orderId?: number
  id?: number
  countryId?: number
  fullName?: string
  gender?: number
  isAdult?: boolean
  age?: number | null
  selectedGuestFromOrder?: number | null
}
