import { ref } from 'vue'

import { MarkupCondition, Time, TimePeriod } from '~api/hotel/markup-settings'

export const useTimeConditions = () => {
  const conditions = ref<MarkupCondition[]>([])
  const minTime = ref<Time>('00:00')
  const maxTime = ref<Time>('24:00')
  const freePeriods = ref<TimePeriod[]>([])

  const refreshFreePeriods = () => {
    conditions.value.sort((a, b) => a.from.localeCompare(b.from))

    let previousTo = minTime.value

    freePeriods.value = []
    conditions.value.forEach(({ from, to }) => {
      if (from > previousTo) {
        freePeriods.value.push({ from: previousTo, to: from })
      }

      previousTo = to
    })

    if (maxTime.value > previousTo) {
      freePeriods.value.push({ from: previousTo, to: maxTime.value })
    }
  }

  const setConditions = (value: MarkupCondition[]): void => {
    conditions.value = value
    refreshFreePeriods()
  }

  const setLimits = (min: Time, max: Time): void => {
    minTime.value = min
    maxTime.value = max
    refreshFreePeriods()
  }

  // const isTimeAvailable = (time: Time): boolean => {
  //   if (!minTime.value && !maxTime.value && conditions.value.length === 0) {
  //     return true
  //   }
  //   const isLessThanMin = Boolean(minTime.value && time < minTime.value)
  //   const isMoreThanMax = Boolean(maxTime.value && time > maxTime.value)
  //   if (isLessThanMin || isMoreThanMax) {
  //     return false
  //   }
  //
  //   const periodWithTimeInside = conditions.value.find((condition: MarkupCondition) => time >= condition.from && time <= condition.to)
  //
  //   return !periodWithTimeInside
  // }

  return {
    freePeriods,
    maxTime,
    minTime,
    setConditions,
    setLimits,
  }
}
