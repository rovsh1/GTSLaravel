import { Ref, ref } from 'vue'

import { ActiveKey } from './index'
import { Day } from './types'

const inRange = (key: string | null, range: string[]): boolean => {
  if (!key) return false
  if (range.includes(key)) {
    return true
  }
  return false
}

type UseQuotasTableRangeParams = {
  days: Ref<Day[]>
  activeKey: Ref<ActiveKey>
  roomID: number
}
export const useTableRange = (params: UseQuotasTableRangeParams) => {
  const {
    activeKey,
    roomID,
    days,
  } = params

  const rangeRef = ref<string[]>([])

  return {
    rangeRef,
    isCellInRange: (key: string | null): boolean => inRange(key, rangeRef.value),
    addItemsToRange: (key: string | null) => {
      const rangeStartPositionIndex = days.value.findIndex((item) => `${item.key}-${roomID}` === activeKey.value)
      const rangeEndPositionIndex = days.value.findIndex((item) => `${item.key}-${roomID}` === key)
      if (rangeStartPositionIndex === -1 || rangeEndPositionIndex === -1) {
        return
      }
      const start = Math.min(rangeStartPositionIndex, rangeEndPositionIndex)
      const end = Math.max(rangeStartPositionIndex, rangeEndPositionIndex) + 1
      const subArray = days.value.slice(start, end)
      rangeRef.value = subArray.map((dayItem) => `${dayItem.key}-${roomID}`)
    },
    addItemToRange: (key: string | null) => {
      if (!key || !activeKey.value) return
      if (inRange(key, rangeRef.value)) {
        const index = rangeRef.value.indexOf(key)
        if (index !== -1) {
          rangeRef.value.splice(index, 1)
        }
      } else {
        rangeRef.value.push(key)
      }
    },
  }
}
