import { DateTime } from 'luxon'

import { Quota, QuotaStatus, roomsMock } from './mock'

export type Day = {
  key: string
  date: Date
  dayOfWeek: string
  monthName: string
  dayOfMonth: string
}

export type RoomQuotaStatus = 'opened' | 'closed'

export type RoomQuota = {
  key: string
  status?: RoomQuotaStatus
  quota: number
  sold: number
  reserve: number
  releaseDays: number
}

export type RoomQuotas = {
  id: number
  label: string
  guests: number
  count: number
  dailyQuota: RoomQuota[]
}

export const quotaStatusMap: Record<QuotaStatus, RoomQuota['status']> = {
  0: 'closed',
  1: 'opened',
}

export const getRoomsQuotasFromQuotas = (quotas: Quota[], days: Day[]) => quotas
  .map(({ room_id: roomID }): RoomQuotas | null => {
    const found = roomsMock.find((room) => roomID === room.id)
    if (found === undefined) return null
    const roomQuotas = quotas.filter((quota) => quota.room_id === roomID)
    const {
      id,
      name,
      guests_number: guests,
      rooms_number: count,
    } = found
    return {
      id,
      label: name,
      guests,
      count,
      dailyQuota: days.map((day): RoomQuota => {
        const data = roomQuotas.find((quota) => DateTime
          .fromFormat(quota.date, 'yyyy-MM-dd')
          .equals(DateTime.fromJSDate(day.date)))
        const key = day.date.getTime().toString()
        if (data === undefined) {
          return {
            key,
            quota: 0,
            sold: 0,
            reserve: 0,
            releaseDays: 0,
          }
        }
        const {
          status,
          release_days: releaseDays,
          count_available: countAvailable,
          count_booked: countBooked,
          count_reserved: countReserved,
        } = data
        return {
          key,
          status: status === undefined ? undefined : quotaStatusMap[status],
          quota: countBooked,
          sold: countAvailable,
          reserve: countReserved,
          releaseDays,
        }
      }),
    }
  })
  .filter((roomType): roomType is RoomQuotas => roomType !== null)
