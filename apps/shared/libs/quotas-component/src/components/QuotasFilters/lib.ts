import { DateTime } from 'luxon'

import { FiltersPayload } from '../lib/types'

export const defaultFiltersPayload: FiltersPayload = {
  dateFrom: DateTime.now().startOf('month').toJSDate(),
  dateTo: DateTime.now().endOf('month').toJSDate(),
  availability: null,
  roomID: null,
}
