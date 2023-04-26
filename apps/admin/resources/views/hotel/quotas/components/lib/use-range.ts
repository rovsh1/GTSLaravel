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

const addRoomIDToRoomQuotaKey = (roomTypeID: RoomID) => (roomQuota: RoomQuota): RoomQuota => ({
  ...roomQuota,
  key: getActiveCellKey(roomQuota.key, roomTypeID),
})

type SetRangeParams = {
  dailyQuota: RoomQuota[]
  roomTypeID: RoomQuotas['id']
  activeKey: ActiveKey
  rangeKey: ActiveKey
}
const setQuotaRange = (params: SetRangeParams) =>
  (done: (newRange: QuotaRange) => void) => {
    const {
      dailyQuota,
      roomTypeID,
      activeKey,
      rangeKey,
    } = params
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
      .map(addRoomIDToRoomQuotaKey(roomTypeID))
    return done({
      roomID: roomTypeID,
      quotas: range,
    })
  }

type SetPickParams = {
  oldRange: QuotaRange
  dailyQuota: RoomQuota[]
  roomTypeID: RoomQuotas['id']
  activeKey: ActiveKey
  pickKey: ActiveKey
}
const setQuotaPick = (params: SetPickParams) =>
  (done: (newRange: QuotaRange) => void) => {
    const {
      oldRange,
      dailyQuota,
      roomTypeID: roomID,
      activeKey,
      pickKey,
    } = params
    const find = (keyToFind: ActiveKey) => {
      const found = dailyQuota
        .find(({ key }) => getActiveCellKey(key, roomID) === keyToFind)
      if (found === undefined) return undefined
      return addRoomIDToRoomQuotaKey(roomID)(found)
    }

    const picked = find(pickKey)
    if (picked === undefined) return done(null)
    if (oldRange === null) {
      const active = find(activeKey)
      if (active === undefined) return done(null)
      // Create new range from active and picked
      return done({ roomID, quotas: [active, picked] })
    }
    const { quotas } = oldRange
    const pickedQuotaInRange = quotas
      .find(({ key }) => key === picked.key)
    if (pickedQuotaInRange === undefined) {
      // Add picked quota to range
      return done({ roomID, quotas: [...quotas, picked] })
    }
    // Remove picked quota from range
    return done({
      roomID,
      quotas: quotas.filter(({ key }) => key !== pickedQuotaInRange.key),
    })
  }

type UseQuotasTableRangeParams = {
  editedRef: Ref<number | null>
  rangeRef: Ref<QuotaRange>
  activeKey: Ref<ActiveKey>
  editedInRange: Ref<EditedQuota | null>
}
export const useQuotasTableRange = (params: UseQuotasTableRangeParams) => {
  const {
    editedRef,
    rangeRef,
    activeKey,
    editedInRange,
  } = params

  const isCellInRange = (cellKey: ActiveKey) =>
    (range: QuotaRange): boolean => {
      if (range === null) return false
      const found = range.quotas.find(({ key }) => key === cellKey)
      return found !== undefined
    }

  watch(activeKey, (value) => {
    if (value !== null) return
    editedRef.value = null
    editedInRange.value = null
  })

  return {
    setPick: (setPickParams: SetPickParams) =>
      setQuotaPick(setPickParams)(((newRange) => {
        rangeRef.value = newRange
      })),
    setRange: (setRangeParams: SetRangeParams) =>
      setQuotaRange(setRangeParams)(((newRange) => {
        rangeRef.value = newRange
      })),
    isCellInRange: (cellKey: ActiveKey) => isCellInRange(cellKey)(rangeRef.value),
    handleInput: (key: ActiveKey, value: number, range: QuotaRange) => {
      editedRef.value = value
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
