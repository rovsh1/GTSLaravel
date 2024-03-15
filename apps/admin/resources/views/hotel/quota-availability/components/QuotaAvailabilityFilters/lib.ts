import { AvailabilityValue } from 'gts-quotas-component/types'
import { DateTime } from 'luxon'

export type FiltersPayload = {
  dateFrom: Date
  dateTo: Date
  cityIds: number[]
  hotelIds: number[]
  roomIds: number[]
  roomTypeIds: number[]
  availability: AvailabilityValue | null
}

export const defaultFiltersPayload: FiltersPayload = {
  dateFrom: DateTime.now().startOf('month').toJSDate(),
  dateTo: DateTime.now().endOf('month').toJSDate(),
  cityIds: [],
  hotelIds: [],
  roomIds: [],
  roomTypeIds: [],
  availability: null,
}
