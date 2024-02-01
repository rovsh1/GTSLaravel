import { onMounted } from 'vue'

import { defineStore } from 'pinia'

import { useHotelMarkupSettingsAPI } from '~api/hotel/markup-settings'

export enum ConditionTypeEnum {
  earlyCheckIn = 'earlyCheckIn',
  lateCheckOut = 'lateCheckOut',
}

export type ConditionType = keyof typeof ConditionTypeEnum

export const useMarkupSettingsStore = defineStore('hotel-markup-settings', () => {
  const { isFetching, execute: fetchMarkupSettings, data: markupSettings } = useHotelMarkupSettingsAPI()

  onMounted(fetchMarkupSettings)

  return {
    isFetching,
    markupSettings,
    fetchMarkupSettings,
  }
})
