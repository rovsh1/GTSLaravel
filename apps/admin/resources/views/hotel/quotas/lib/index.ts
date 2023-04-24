import { uniqBy } from 'lodash'
import { DateTime } from 'luxon'

import { Quota, QuotaStatus, roomsMock } from './mock'

export type Day = {
  key: string
  date: Date
  dayOfWeek: string
  dayOfMonth: string
}

export type RoomQuotaStatus = 'opened' | 'closed'

export type RoomID = number

export type RoomQuota = {
  key: string
  id: Quota['id'] | null
  roomID: RoomID
  date: Date
  status?: RoomQuotaStatus
  quota: number
  sold: number
  reserve: number
  releaseDays: number
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

export const getRoomsQuotasFromQuotas = (quotas: Quota[], days: Day[]) => uniqBy(
  quotas
    .map(({ room_id: roomID }): RoomQuotas | null => {
      const room = roomsMock.find(({ id }) => roomID === id)
      if (room === undefined) return null
      const roomQuotas = quotas.filter((quota) => quota.room_id === roomID)
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
              quota: 0,
              sold: 0,
              reserve: 0,
              releaseDays: 0,
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
            status: status === undefined ? undefined : quotaStatusMap[status],
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
