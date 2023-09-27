import { DateTime } from 'luxon'

import { FiltersPayload } from '~resources/views/hotel/quotas/components/QuotasFilters/lib'

import { HotelRoomID } from '~api/hotel'
import { HotelQuotaID, QuotaStatus, UseHotelQuota } from '~api/hotel/quotas/list'
import { UseHotelRooms } from '~api/hotel/rooms'

import { getEachDayInMonth, isBusinessDay } from '~lib/date'

export type Day = {
  key: string
  date: Date
  dayOfWeek: string
  dayOfMonth: string
  isHoliday: boolean
}

export type RoomQuotaStatus = 'opened' | 'closed'

export type RoomQuota = {
  key: string
  id: HotelQuotaID | null
  roomID: HotelRoomID
  date: Date
  status: RoomQuotaStatus | null
  quota: number | null
  sold: number | null
  reserve: number | null
  releaseDays: number | null
}

const quotaStatusMap: Record<QuotaStatus, RoomQuotaStatus | undefined> = {
  0: 'closed',
  1: 'opened',
}

const getMonthKey = (year: string | number, month: string | number): string => `${year}-${month}`

const monthKeyFormat = 'yyyy-M'
const quotaDateFormat = 'yyyy-MM-dd'

export type RoomRender = {
  id: HotelRoomID
  label: string
  guests: number
  count: number
}

export type MonthlyQuota = {
  monthKey: string
  dailyQuota: RoomQuota[]
  monthName: string
  days: Day[]
  quotasCount: number
}

export type RoomQuotas = {
  room: RoomRender
  monthlyQuotas: MonthlyQuota[]
}

type GetRoomQuotas = (params: {
  rooms: UseHotelRooms
  filters: FiltersPayload
  quotas: UseHotelQuota
}) => RoomQuotas[] | null

export const getRoomQuotas: GetRoomQuotas = ({ rooms, filters, quotas }) => {
  if (!rooms) return null

  const { year, month, monthsCount } = filters

  return rooms.map((room) => {
    const {
      id,
      name: label,
      guestsCount: guests,
      roomsNumber: count,
    } = room

    const startDate = DateTime.fromFormat(getMonthKey(year, month), monthKeyFormat)
    const endDate = startDate.plus({ months: monthsCount })

    const roomQuotasMap = new Map<string, RoomQuota>()

    if (quotas) {
      quotas
        .filter((hotelQuota) => hotelQuota.roomID === id)
        .forEach((hotelQuota) => {
          const key = DateTime.fromFormat(hotelQuota.date, quotaDateFormat)
            .toJSDate()
            .getTime()
            .toString()
          roomQuotasMap.set(key, {
            key,
            id: hotelQuota.id,
            roomID: id,
            date: new Date(key),
            status: quotaStatusMap[hotelQuota.status] || null,
            quota: hotelQuota.count_available,
            sold: hotelQuota.count_booked,
            reserve: hotelQuota.count_reserved,
            releaseDays: hotelQuota.release_days,
          })
        })
    }

    const monthlyQuotas: MonthlyQuota[] = []

    let currentDate = startDate
    while (currentDate < endDate) {
      const monthDate = currentDate.toJSDate()
      const days = getEachDayInMonth(monthDate).map((date) => {
        const dt = DateTime.fromJSDate(date)
        return {
          key: date.getTime().toString(),
          date,
          dayOfWeek: dt.toFormat('EEE'),
          dayOfMonth: dt.toFormat('d'),
          isHoliday: !isBusinessDay(date),
        }
      })

      monthlyQuotas.push({
        monthKey: currentDate.toFormat(monthKeyFormat),
        dailyQuota: days.map(({ key }) => roomQuotasMap.get(key) || ({
          key,
          id: null,
          roomID: id,
          date: new Date(parseInt(key, 10)),
          status: null,
          quota: null,
          sold: null,
          reserve: null,
          releaseDays: null,
        })),
        days,
        monthName: currentDate.toFormat('LLLL yyyy'),
        quotasCount: days.filter(({ date }) => roomQuotasMap.has(date.getTime().toString())).length,
      })

      currentDate = currentDate.plus({ months: 1 })
    }

    return {
      id,
      room: { id, label, guests, count },
      monthlyQuotas,
    }
  })
}

export type ActiveKey = string | null

export const getActiveCellKey = (dayKey: string, roomTypeID: number): string =>
  `${dayKey}-${roomTypeID}`
