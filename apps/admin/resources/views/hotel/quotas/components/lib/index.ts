import { DateTime } from 'luxon'

import { FiltersPayload } from '~resources/views/hotel/quotas/components/QuotasFilters/lib'

import { HotelRoomID } from '~api/hotel'
import { HotelQuotaID, QuotaStatus, UseHotelQuota } from '~api/hotel/quotas/list'

import { isBusinessDay } from '~helpers/date'

export type RoomQuotaStatus = 'opened' | 'closed' | 'warning'

export type RoomQuota = {
  key: string
  id: HotelQuotaID | null
  roomID: HotelRoomID
  date: string
  status: RoomQuotaStatus | null
  quota: number | null
  sold: number | null
  reserve: number | null
  releaseDays: number | null
}

export type Day = {
  key: string
  date: string
  dayOfWeek: string
  dayOfMonth: string
  isHoliday: boolean
  monthKey: string
  monthName: string
  isLastDayInMonth: boolean
  dayQuota: RoomQuota | null
}

export type Month = {
  monthKey: string
  monthName: string
  daysCount: number
}

const quotaStatusMap: Record<QuotaStatus, RoomQuotaStatus | undefined> = {
  0: 'closed',
  1: 'opened',
}

const monthKeyFormat = 'yyyy-M'
const quotaDateFormat = 'yyyy-MM-dd'

export type RoomRender = {
  id: HotelRoomID
  label: string
  guests: number
  count: number
}

export type QuotasAccumalationData = {
  period: Day[]
  months: Month[]
  quotas: Map<string, RoomQuota>
}

type GetRoomQuotas = (params: {
  filters: FiltersPayload
  quotas: UseHotelQuota
}) => QuotasAccumalationData

export const getRoomQuotas: GetRoomQuotas = ({ filters, quotas }) => {
  const { dateFrom, dateTo } = filters

  const roomQuotasMap = new Map<string, RoomQuota>()

  if (quotas) {
    quotas
      .forEach((hotelQuota) => {
        const key = DateTime.fromFormat(hotelQuota.date, quotaDateFormat)
          .toJSDate()
          .getTime()
          .toString()
        roomQuotasMap.set(`${key}-${hotelQuota.roomID}`, {
          key: `${key}-${hotelQuota.roomID}`,
          id: hotelQuota.id,
          roomID: hotelQuota.roomID,
          date: hotelQuota.date,
          status: quotaStatusMap[hotelQuota.status] || null,
          quota: hotelQuota.count_available,
          sold: hotelQuota.count_booked,
          reserve: hotelQuota.count_reserved,
          releaseDays: hotelQuota.release_days,
        })
      })
  }

  const startDate = DateTime.fromJSDate(dateFrom)
  const endDate = DateTime.fromJSDate(dateTo)
  const eachMonth: Month[] = []
  const eachDays: Day[] = []
  let currentDate = startDate
  while (currentDate <= endDate) {
    const monthKey = currentDate.toFormat(monthKeyFormat)
    const monthName = currentDate.toFormat('LLLL yyyy')

    const isMonthInArray = eachMonth.some((item) => item.monthKey === monthKey)
    if (!isMonthInArray) {
      eachMonth.push({
        monthKey,
        monthName,
        daysCount: 0,
      })
    }

    const date = currentDate.toJSDate()
    eachDays.push({
      key: date.getTime().toString(),
      date: currentDate.toFormat(quotaDateFormat),
      dayOfWeek: currentDate.toFormat('EEE'),
      dayOfMonth: currentDate.toFormat('d'),
      isHoliday: !isBusinessDay(date),
      isLastDayInMonth: currentDate.endOf('month').toISODate() === currentDate.toISODate(),
      monthKey,
      monthName,
      dayQuota: null,
    })

    currentDate = currentDate.plus({ days: 1 })
  }

  eachMonth.forEach((month) => {
    const source = month
    source.daysCount = eachDays.filter((days) => source.monthKey === days.monthKey).length
  })

  return {
    period: eachDays || [],
    months: eachMonth || [],
    quotas: roomQuotasMap,
  }
}

export type ActiveKey = string | null

export const getActiveCellKey = (dayKey: string, roomTypeID: number): string =>
  `${dayKey}-${roomTypeID}`
