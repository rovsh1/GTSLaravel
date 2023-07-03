import { onMounted } from 'vue'

import { defineStore } from 'pinia'
import { z } from 'zod'

import { useHotelMarkupSettingsAPI } from '~api/hotel/markup-settings'

import { requestInitialData } from '~lib/initial-data'

const { hotelID } = requestInitialData(
  'view-initial-data-hotel-settings',
  z.object({
    hotelID: z.number(),
  }),
)

export enum ConditionTypeEnum {
  earlyCheckIn = 'earlyCheckIn',
  lateCheckOut = 'lateCheckOut',
}

export type ConditionType = keyof typeof ConditionTypeEnum

export const useMarkupSettingsStore = defineStore('hotel-markup-settings', () => {
  const { isFetching, execute: fetchMarkupSettings, data: markupSettings } = useHotelMarkupSettingsAPI({ hotelID })

  onMounted(fetchMarkupSettings)

  return {
    isFetching,
    markupSettings,
    fetchMarkupSettings,
  }
})
