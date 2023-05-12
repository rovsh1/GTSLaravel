import { Ref, ref, watch } from 'vue'

import { HotelRoomID } from '~api/hotel'

import { ActiveKey, getActiveCellKey, RoomQuota } from '.'

export type QuotaRange = {
  roomID: HotelRoomID
  quotas: RoomQuota[]
} | null

export type EditedQuota = {
  key: ActiveKey
  value: number
}

const addRoomIDToRoomQuotaKey = (roomTypeID: HotelRoomID) => (roomQuota: RoomQuota): RoomQuota => ({
  ...roomQuota,
  key: getActiveCellKey(roomQuota.key, roomTypeID),
})

type SetRangeParams = {
  dailyQuota: RoomQuota[]
  roomTypeID: HotelRoomID
  activeKey: ActiveKey
  rangeKey: ActiveKey
}
const setQuotaRange = (params: SetRangeParams) =>
  (roomQuotas: RoomQuota[], done: (newRange: QuotaRange) => void) => {
    const {
      roomTypeID,
      activeKey,
      rangeKey,
    } = params
    const indexes: [number, number] = [-1, -1]
    roomQuotas.forEach(({ key }, index) => {
      if (getActiveCellKey(key, roomTypeID) === activeKey) indexes[0] = index
      if (getActiveCellKey(key, roomTypeID) === rangeKey) indexes[1] = index
    })
    const [firstIndex, lastIndex] = indexes
    if (firstIndex === -1 || lastIndex === -1) {
      return done(null)
    }
    if (firstIndex > lastIndex) indexes.reverse()
    const [from, to] = indexes
    const range = roomQuotas
      .slice(from, to + 1)
      .map(addRoomIDToRoomQuotaKey(roomTypeID))
    return done({
      roomID: roomTypeID,
      quotas: range,
    })
  }

type SetPickParams = {
  oldRange: QuotaRange
  roomTypeID: HotelRoomID
  activeKey: ActiveKey
  pickKey: ActiveKey
}
const setQuotaPick = (params: SetPickParams) =>
  (roomQuotas: RoomQuota[], done: (newRange: QuotaRange) => void) => {
    const {
      oldRange,
      roomTypeID: roomID,
      activeKey,
      pickKey,
    } = params
    const find = (keyToFind: ActiveKey) => {
      const found = roomQuotas
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
  roomQuotas: Ref<RoomQuota[]>
  editedRef: Ref<number | null>
  activeKey: Ref<ActiveKey>
  editedInRange: Ref<EditedQuota | null>
}
export const useQuotasTableRange = (params: UseQuotasTableRangeParams) => {
  const {
    roomQuotas,
    editedRef,
    activeKey,
    editedInRange,
  } = params

  const rangeRef = ref<QuotaRange>(null)

  watch(activeKey, (value) => {
    if (value === null) rangeRef.value = null
  })

  return {
    rangeRef,
    isCellInRange: (cellKey: ActiveKey): boolean => {
      if (rangeRef.value === null) return false
      const found = rangeRef.value.quotas.find(({ key }) => key === cellKey)
      return found !== undefined
    },
    setPick: (setPickParams: SetPickParams) =>
      setQuotaPick(setPickParams)(roomQuotas.value, ((newRange) => {
        rangeRef.value = newRange
      })),
    setRange: (setRangeParams: SetRangeParams) =>
      setQuotaRange(setRangeParams)(roomQuotas.value, ((newRange) => {
        rangeRef.value = newRange
      })),
    handleInput: (key: ActiveKey, value: number) => {
      editedRef.value = value
      if (rangeRef.value === null) {
        editedInRange.value = null
        return
      }
      editedInRange.value = { key, value }
    },
    showEdited: (key: ActiveKey): boolean => {
      if (editedInRange.value === null) return false
      if (rangeRef.value === null) return false
      return rangeRef.value.quotas
        .find((quota) => quota.key === key) !== undefined
    },
  }
}
