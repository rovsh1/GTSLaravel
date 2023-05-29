import { Ref } from 'vue'

import { MarkupCondition } from '~api/hotel/markup-settings'

export interface RoomFormData {
  bookingID: number
  id?: number
  status?: number
  roomCount?: number
  residentType?: number
  discount?: number
  rateId?: number
  note?: string
  earlyCheckIn?: MarkupCondition
  lateCheckOut?: MarkupCondition
  roomIndex?: number
}

export interface GuestFormData {
  bookingID: number
  roomIndex?: number
  guestIndex?: number
  countryId?: number
  fullName?: string
  gender?: number
}

export const validateForm = <T>(form: Ref<HTMLFormElement>, data: Ref<T>): data is Ref<Required<T>> => Boolean(form.value?.reportValidity())
