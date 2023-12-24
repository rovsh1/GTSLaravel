import { onMounted, ref } from 'vue'

import { Time } from '~api/hotel/markup-settings'
import { TimeSettings, updateHotelSettings, useHotelSettingsAPI } from '~api/hotel/settings'

export const useTimeSettings = (hotelID: number) => {
  const { data: hotelSettings, execute: fetchTimeSettings, onFetchResponse } = useHotelSettingsAPI({ hotelID })
  const checkInAfter = ref<Time>()
  const checkOutBefore = ref<Time>()
  const breakfastPeriodFrom = ref<Time>()
  const breakfastPeriodTo = ref<Time>()

  const updateTimeSettings = async (): Promise<void> => {
    let breakfastPeriod = null
    if (breakfastPeriodFrom.value && breakfastPeriodTo.value) {
      breakfastPeriod = {
        from: breakfastPeriodFrom.value,
        to: breakfastPeriodTo.value,
      }
    }

    const timeSettings: TimeSettings = {
      checkOutBefore: checkOutBefore.value as Time,
      checkInAfter: checkInAfter.value as Time,
      breakfastFrom: breakfastPeriod?.from || null,
      breakfastTo: breakfastPeriod?.to || null,
    }
    await updateHotelSettings({ hotelID, timeSettings })
  }

  onFetchResponse(() => {
    const timeSettings = hotelSettings.value?.timeSettings
    if (timeSettings) {
      if (timeSettings.breakfastFrom && timeSettings.breakfastTo) {
        breakfastPeriodFrom.value = timeSettings.breakfastFrom
        breakfastPeriodTo.value = timeSettings.breakfastTo
      }
      checkInAfter.value = timeSettings.checkInAfter
      checkOutBefore.value = timeSettings.checkOutBefore
    }
  })

  onMounted(fetchTimeSettings)

  return {
    checkInAfter,
    checkOutBefore,
    breakfastPeriodFrom,
    breakfastPeriodTo,
    fetchTimeSettings,
    updateTimeSettings,
  }
}
