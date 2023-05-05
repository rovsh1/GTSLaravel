import { MonthNumber, QueryInterval } from '~resources/lib/api/hotel/quotas'
import { HotelRoomID } from '~resources/lib/api/hotel/room'

export type Year = {
  label: string
  value: number
}

export const createYear = (value: number): Year => ({
  label: value.toString(),
  value,
})

export type Month = {
  label: string
  value: MonthNumber
}

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
  availability: AvailabilityValue | null
  roomID: HotelRoomID | null
}

export const defaultFiltersPayload: FiltersPayload = {
  year: new Date().getFullYear(),
  month: new Date().getMonth() + 1 as MonthNumber,
  monthsCount: 1,
  availability: null,
  roomID: null,
}
