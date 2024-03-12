import { DateTime } from 'luxon'

import { AvailabilityValue } from '~resources/views/hotel/quotas/components/QuotasFilters/lib'

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
