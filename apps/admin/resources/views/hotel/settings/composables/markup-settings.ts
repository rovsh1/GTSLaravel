import { onMounted } from 'vue'

import { defineStore } from 'pinia'
import { z } from 'zod'

import {
  deleteConditionHotelMarkupSettings,
  updateConditionHotelMarkupSettings,
  useHotelMarkupSettingsAPI,
} from '~api/hotel/markup-settings'

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

export interface DeleteDailyMarkupPayload {
  cancelPeriodIndex: number
  dailyMarkupIndex: number
}

export interface UpdateDailyMarkupPayload {
  cancelPeriodIndex: number
  dailyMarkupIndex: number
  field: string
  value: number
}

export const useMarkupSettingsStore = defineStore('hotel-markup-settings', () => {
  const { isFetching, execute: fetchMarkupSettings, data: markupSettings } = useHotelMarkupSettingsAPI({ hotelID })

  onMounted(fetchMarkupSettings)

  const updateCancelPeriodDailyMarkupField = async (payload: UpdateDailyMarkupPayload) => {
    const key = `cancelPeriods.${payload.cancelPeriodIndex}.dailyMarkups.${payload.dailyMarkupIndex}.${payload.field}`
    const request = {
      hotelID,
      key,
      value: payload.value,
    }
    await updateConditionHotelMarkupSettings(request)
    await fetchMarkupSettings()
  }

  const deleteCancelPeriodDailyMarkup = async (payload: DeleteDailyMarkupPayload) => {
    const key = `cancelPeriods.${payload.cancelPeriodIndex}.dailyMarkups`
    const request = {
      hotelID,
      key,
      index: payload.dailyMarkupIndex,
    }
    await deleteConditionHotelMarkupSettings(request)
    await fetchMarkupSettings()
  }

  return {
    isFetching,
    markupSettings,
    fetchMarkupSettings,
    updateCancelPeriodDailyMarkupField,
    deleteCancelPeriodDailyMarkup,
  }
})
