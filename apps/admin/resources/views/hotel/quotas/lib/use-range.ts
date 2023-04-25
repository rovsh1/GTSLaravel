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
    const indexes: [number, number] = [-1, -1]
    dailyQuota.forEach(({ key }, index) => {
      if (getActiveCellKey(key, roomTypeID) === activeKey) indexes[0] = index
      if (getActiveCellKey(key, roomTypeID) === rangeKey) indexes[1] = index
    })
    const [firstIndex, lastIndex] = indexes
    if (firstIndex === -1 || lastIndex === -1) {
      return done(null)
    }
    if (firstIndex > lastIndex) indexes.reverse()
    const [from, to] = indexes
    const range = dailyQuota
      .slice(from, to + 1)
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
