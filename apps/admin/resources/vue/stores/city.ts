import { onMounted, ref } from 'vue'

import { defineStore } from 'pinia'

import { CacheStorage } from '~resources/lib/cache-storage/cache-storage'
import { TTLValues } from '~resources/lib/enums'

import { CityResponse, useCitySearchAPI } from '~api/city'

export const useCityStore = defineStore('city', () => {
  const cities = ref<CityResponse[] | null>(null)
  onMounted(async () => {
    cities.value = await CacheStorage.remember('cities', TTLValues.DAY, async () => {
      const { data, execute: fetchCities } = useCitySearchAPI({})
      await fetchCities()
      return data.value
    }) as CityResponse[]
  })
  return {
    cities,
  }
})
