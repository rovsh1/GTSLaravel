import { DateTime } from 'luxon'

export type FiltersPayload = {
  dateFrom: Date
  dateTo: Date
  cityIds: number[] | null
  hotelIds: number[] | null
  roomIds: number[] | null
}

export const defaultFiltersPayload: FiltersPayload = {
  dateFrom: DateTime.now().startOf('month').toJSDate(),
  dateTo: DateTime.now().endOf('month').toJSDate(),
  cityIds: null,
  hotelIds: null,
  roomIds: null,
}
