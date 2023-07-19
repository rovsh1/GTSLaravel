import { ref } from 'vue'

import { MarkupCondition, Time, TimePeriod } from '~api/hotel/markup-settings'

export const useTimeConditions = () => {
  const conditions = ref<MarkupCondition[]>([])
  const minTime = ref<Time>('00:00')
  const maxTime = ref<Time>('24:00')
  const freePeriods = ref<TimePeriod[]>([{ from: minTime.value, to: maxTime.value }])

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

  return {
    freePeriods,
    maxTime,
    minTime,
    setConditions,
    setLimits,
  }
}
