import { MarkupCondition } from '~api/hotel/markup-settings'

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

export interface CarFormData {
  bookingID: number
  orderId?: number
  id?: number
  carModelId: number
  carCount: number
  passengerCount: number
  baggageCount: number
  selectedCarFromOrder?: number | null
}
