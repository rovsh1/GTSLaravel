import { Ref, watch } from 'vue'

import { ActiveKey, getActiveCellKey, RoomID, RoomQuota, RoomQuotas } from './index'

export type QuotaRange = {
  roomID: RoomID
  quotas: RoomQuota[]
} | null

export type EditedQuota = {
  key: ActiveKey
  value: number
}

type SetRangeParams = {
  dailyQuota: RoomQuota[]
  roomTypeID: RoomQuotas['id']
  activeKey: ActiveKey
  rangeKey: ActiveKey
}
const setQuotaRange = (params: SetRangeParams) =>
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
  activeKey: Ref<ActiveKey>
  editedInRange: Ref<EditedQuota | null>
}
export const useQuotasTableRange = (params: UseQuotasTableRangeParams) => {
  const { rangeRef, activeKey, editedInRange } = params

  const isCellInRange = (cellKey: ActiveKey) =>
    (range: QuotaRange): boolean => {
      if (range === null) return false
      const found = range.quotas.find(({ key }) => key === cellKey)
      return found !== undefined
    }

  watch(activeKey, (value) => {
    if (value !== null) return
    editedInRange.value = null
  })

  return {
    setRange: (setRangeParams: SetRangeParams) =>
      setQuotaRange(setRangeParams)(((value) => {
        rangeRef.value = value
      })),
    isCellInRange: (cellKey: ActiveKey) => isCellInRange(cellKey)(rangeRef.value),
    handleInput: (key: ActiveKey, value: number, range: QuotaRange) => {
      if (range === null) return
      editedInRange.value = { key, value }
    },
    showEdited: (key: ActiveKey, range: QuotaRange): boolean => {
      if (editedInRange.value === null) return false
      if (range === null) return false
      return range.quotas
        .find((quota) => quota.key === key) !== undefined
    },
  }
}
