import { onMounted, ref } from 'vue'

import { defineStore } from 'pinia'

import { RoomTypesResponse, useRoomTypesGetAPI } from '~api/room-type'

import { CacheStorage } from '~cache/cache-storage'
import { TTLValues } from '~cache/enums'

export const useRoomTypesStore = defineStore('room-types', () => {
  const roomTypes = ref<RoomTypesResponse[] | null>(null)
  onMounted(async () => {
    roomTypes.value = await CacheStorage.remember('room-types', TTLValues.HOUR, async () => {
      const { data, execute: fetchRoomTypes } = useRoomTypesGetAPI()
      await fetchRoomTypes()
      return data.value
    }) as RoomTypesResponse[]
  })

  return {
    roomTypes,
  }
})
