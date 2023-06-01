import { MaybeRef } from '@vueuse/core'

import { useAdminAPI } from '~api'
import { BookingID } from '~api/booking/index'
import { DailyMarkup, MarkupCondition, NoCheckInMarkup, Percent } from '~api/hotel/markup-settings'

export interface BookingHotelDetailsPayload {
  bookingID: BookingID
}

export interface HotelBookingGuest {
  id: number
  fullName: string
  countryId: number
  gender: number
}

export interface HotelBookingDetailsRoom {
  id: number
  rateId: number
  status: number
  roomCount: number
  guests: HotelBookingGuest[]
  guestNote: string
  discount: Percent
  isResident: boolean
  earlyCheckIn: MarkupCondition
  lateCheckOut: MarkupCondition
}

export enum ExternalNumberTypeEnum {
  GotoStansBookingNumber = 1,
  HotelBookingNumber = 2,
  FullName = 3,
}

const ExternalNumberTypes = {
  GotoStansBookingNumber: 1,
  HotelBookingNumber: 2,
  FullName: 3,
} as const

export type ExternalNumberType = typeof ExternalNumberTypes[keyof typeof ExternalNumberTypes]

export interface ExternalNumber {
  type: ExternalNumberType
  number: string | null
}

export interface AdditionalInfo {
  externalNumber: ExternalNumber | null
}

export interface CancelConditions {
  cancelNoFeeDate: string | null
  noCheckInMarkup: NoCheckInMarkup
  dailyMarkups: DailyMarkup[]
}

export interface HotelBookingDetails {
  id: number
  rooms: HotelBookingDetailsRoom[]
  additionalInfo: AdditionalInfo | null
  cancelConditions: CancelConditions
}

export const useBookingHotelDetailsAPI = (props: MaybeRef<BookingHotelDetailsPayload | null>) =>
  useAdminAPI(props, ({ bookingID }) =>
    `/hotel-booking/${bookingID}/details`)
    .get()
    .json<HotelBookingDetails>()
