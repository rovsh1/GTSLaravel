import { DateTime, Interval } from 'luxon'

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

export const quotaStatusMap: Record<QuotaStatus, RoomQuota['status']> = {
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
export const getRoomQuotas: GetRoomQuotas = (params) => {
  const { rooms, filters, quotas } = params

  if (rooms === null) return null

  const { year, month, monthsCount, roomID } = filters

  const selectedRooms = roomID === null
    ? rooms
    : rooms.filter(({ id }) => id === roomID)

  return selectedRooms.map((room): RoomQuotas => {
    const {
      id,
      name: label,
      guestsCount: guests,
      roomsNumber: count,
    } = room

    const startDate = DateTime.fromFormat(getMonthKey(year, month), monthKeyFormat)

    const roomQuotas = quotas === null
      ? []
      : quotas.filter((hotelQuota) => hotelQuota.roomID === id)

    return {
      room: { id, label, guests, count },
      monthlyQuotas: Interval
        .fromDateTimes(startDate, startDate.plus({ months: monthsCount }))
        .splitBy({ months: 1 })
        .map((interval) => {
          if (interval.isValid) return interval.start
          throw new Error('Interval is invalid')
        })
        .filter((dateTime): dateTime is DateTime => dateTime !== null)
        .map((dateTime): MonthlyQuota => {
          const monthDate = dateTime.toJSDate()
          const days = getEachDayInMonth(monthDate)
            .map((date): Day => {
              const dt = DateTime.fromJSDate(date)
              return ({
                key: date.getTime().toString(),
                date,
                dayOfWeek: dt.toFormat('EEE'),
                dayOfMonth: dt.toFormat('d'),
                isHoliday: !isBusinessDay(date),
              })
            })

          return {
            monthKey: dateTime.toFormat(monthKeyFormat),
            dailyQuota: days.map(({ date }): RoomQuota => {
              const foundQuota = roomQuotas
                .find((quota) => DateTime
                  .fromFormat(quota.date, quotaDateFormat)
                  .equals(DateTime.fromJSDate(date)))
              const key = date.getTime().toString()
              const common = {
                key,
                date,
                roomID: id,
              }
              if (foundQuota === undefined) {
                return {
                  ...common,
                  id: null,
                  status: null,
                  quota: null,
                  sold: null,
                  reserve: null,
                  releaseDays: null,
                }
              }
              const {
                id: quotaID,
                status,
                release_days: releaseDays,
                // count_available: countAvailable,
                count_total: countTotal,
                count_booked: countBooked,
                count_reserved: countReserved,
              } = foundQuota
              return {
                ...common,
                id: quotaID,
                status: quotaStatusMap[status],
                quota: countTotal,
                sold: countBooked,
                reserve: countReserved,
                releaseDays,
              }
            }),
            days,
            // TODO dynamic year display
            monthName: DateTime.fromJSDate(monthDate).toFormat('LLLL yyyy'),
            quotasCount: roomQuotas.filter(({ date }) => days
              .find((day) => DateTime
                .fromJSDate(day.date)
                .equals(DateTime.fromFormat(date, quotaDateFormat))))
              .length,
          }
        }),
    }
  })
}

export type ActiveKey = string | null

export const getActiveCellKey = (dayKey: string, roomTypeID: number): string =>
  `${dayKey}-${roomTypeID}`
