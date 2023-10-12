import { DateTime } from 'luxon'

import { HotelRoomID } from '~api/hotel'

export type AvailabilityValue = 'sold' | 'stopped' | 'available'

export type FiltersPayload = {
  dateFrom: Date
  dateTo: Date
  availability: AvailabilityValue | null
  roomID?: HotelRoomID | null
}

export const defaultFiltersPayload: FiltersPayload = {
  dateFrom: DateTime.now().startOf('month').toJSDate(),
  dateTo: DateTime.now().endOf('month').toJSDate(),
  availability: null,
  roomID: null,
}
