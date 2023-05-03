import { DateTime } from 'luxon'

import { RoomID } from '~resources/views/hotel/quotas/components/lib'
import { MonthNumber, QueryInterval } from '~resources/lib/api/hotel/quotas'

export type Year = {
  label: string
  value: number
}

export const createYear = (value: number): Year => ({
  label: value.toString(),
  value,
})

export const currentYear = new Date().getFullYear()

export type Month = {
  label: string
  value: MonthNumber
}

export const currentMonth = Number(DateTime.now().toFormat('L')) as MonthNumber

export type MonthsCount = 1 | 3 | 6 | 12 | 24 | 36

export const intervalByMonthsCount: Record<MonthsCount, QueryInterval> = {
  1: 'P1M',
  3: 'P3M',
  6: 'P6M',
  12: 'P1Y',
  24: 'P2Y',
  36: 'P3Y',
}

export type AvailabilityValue = 'sold' | 'stopped' | 'available'

export type FiltersPayload = {
  year: number
  month: MonthNumber
  monthsCount: MonthsCount
  availability?: AvailabilityValue
  room?: RoomID
}

export const defaultFiltersPayload: FiltersPayload = {
  year: currentYear,
  month: currentMonth,
  // count: 1,
  monthsCount: 12,
}
