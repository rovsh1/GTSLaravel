import { uniqBy } from 'lodash'
import { DateTime } from 'luxon'

import { HotelQuota, HotelQuotaID, QuotaStatus } from '~resources/lib/api/hotel/quotas'
import { HotelRoom } from '~resources/lib/api/hotel/rooms'

export type Day = {
  key: string
  date: Date
  dayOfWeek: string
  dayOfMonth: string
  isHoliday: boolean
}

export type RoomQuotaStatus = 'opened' | 'closed'

export type RoomID = number

export type RoomQuota = {
  key: string
  id: HotelQuotaID | null
  roomID: RoomID
  date: Date
  status: RoomQuotaStatus | null
  quota: number | null
  sold: number | null
  reserve: number | null
  releaseDays: number | null
}

export type RoomQuotas = {
  id: RoomID
  label: string
  customName: string
  guests: number
  count: number
  dailyQuota: RoomQuota[]
}

export const quotaStatusMap: Record<QuotaStatus, RoomQuota['status']> = {
  0: 'closed',
  1: 'opened',
}

type RoomsQuotasFromQuotasParams = {
  rooms: HotelRoom[]
  quotas: HotelQuota[]
  days: Day[]
}
export const getRoomsQuotasFromQuotas = ({
  rooms, quotas, days,
}: RoomsQuotasFromQuotasParams) => uniqBy(
  quotas
    .map(({ roomID }): RoomQuotas | null => {
      const room = rooms.find(({ id }) => roomID === id)
      if (room === undefined) return null
      const roomQuotas = quotas
        .filter((quota) => quota.roomID === roomID)
      const {
        id,
        name,
        custom_name: customName,
        guests_number: guests,
        rooms_number: count,
      } = room
      return {
        id,
        label: name,
        customName,
        guests,
        count,
        dailyQuota: days.map(({ date }): RoomQuota => {
          const foundQuota = roomQuotas.find((quota) => DateTime
            .fromFormat(quota.date, 'yyyy-MM-dd')
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
            count_available: countAvailable,
            count_booked: countBooked,
            count_reserved: countReserved,
          } = foundQuota
          return {
            ...common,
            id: quotaID,
            status: quotaStatusMap[status],
            quota: countBooked,
            sold: countAvailable,
            reserve: countReserved,
            releaseDays,
          }
        }),
      }
    })
    .filter((roomType): roomType is RoomQuotas => roomType !== null),
  ((room) => room.id),
)

export type ActiveKey = string | null

export const getActiveCellKey = (dayKey: string, roomTypeID: number): string =>
  `${dayKey}-${roomTypeID}`
