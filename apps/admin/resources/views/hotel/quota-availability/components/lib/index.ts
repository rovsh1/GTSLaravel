import { isBusinessDay } from 'gts-common/helpers/date'
import { DateTime } from 'luxon'

import { Day, Month, monthKeyFormat, quotaDateFormat, quotaStatusMap, RoomQuota } from '~resources/views/hotel/quotas/components/lib'
import { FiltersPayload } from '~resources/views/hotel/quotas/components/QuotasFilters/lib'

import { UseHotelQuota } from '~api/hotel/quotas/list'

export type QuotasPeriod = {
  period: Day[]
  months: Month[]
}

type GetQuotasPeriod = (params: {
  filters: FiltersPayload
}) => QuotasPeriod

type GetRoomQuotas = (quotas: UseHotelQuota) => Map<string, RoomQuota>

export const getQuotasPeriod: GetQuotasPeriod = ({ filters }) => {
  const { dateFrom, dateTo } = filters

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
  }
}

export const getRoomQuotas: GetRoomQuotas = (quotas) => {
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

  return roomQuotasMap
}
