import { onMounted, ref } from 'vue'

import { defineStore } from 'pinia'

import { CityResponse, useCitySearchAPI } from '~api/city'

import { CacheStorage } from '~cache/cache-storage'
import { TTLValues } from '~cache/enums'

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
