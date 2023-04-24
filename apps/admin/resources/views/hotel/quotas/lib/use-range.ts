import { Ref } from 'vue'

import { ActiveKey, getActiveCellKey, RoomID, RoomQuota, RoomQuotas } from './index'

export type QuotaRange = {
  roomID: RoomID
  quotas: RoomQuota[]
} | null

type SetRangeParams = {
  dailyQuota: RoomQuota[]
  roomTypeID: RoomQuotas['id']
  activeKey: ActiveKey
  rangeKey: ActiveKey
}
const setRange = (params: SetRangeParams) =>
  (done: (range: QuotaRange) => void) => {
    const { dailyQuota, roomTypeID, activeKey, rangeKey } = params
    let firstIndex: number = -1
    let lastIndex: number = -1
    dailyQuota.forEach(({ key }, index) => {
      if (getActiveCellKey(key, roomTypeID) === activeKey) firstIndex = index
      if (getActiveCellKey(key, roomTypeID) === rangeKey) lastIndex = index
    })
    if (firstIndex === -1 || lastIndex === -1) {
      return done(null)
    }
    const range = dailyQuota
      .slice(firstIndex, lastIndex + 1)
      .map(({ key, ...rest }) => ({ key: getActiveCellKey(key, roomTypeID), ...rest }))
    return done({
      roomID: roomTypeID,
      quotas: range,
    })
  }

type UseQuotasTableRangeParams = {
  rangeRef: Ref<QuotaRange>
}
export const useQuotasTableRange = (params: UseQuotasTableRangeParams) => {
  const { rangeRef } = params

  const isCellInRange = (cellKey: ActiveKey) =>
    (range: QuotaRange): boolean => {
      if (range === null) return false
      const found = range.quotas.find(({ key }) => key === cellKey)
      return found !== undefined
    }

  return {
    setRange: (setRangeParams: SetRangeParams) =>
      setRange(setRangeParams)(((value) => {
        rangeRef.value = value
      })),
    isCellInRange: (cellKey: ActiveKey) => isCellInRange(cellKey)(rangeRef.value),
  }
}
