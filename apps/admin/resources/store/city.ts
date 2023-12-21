import { onMounted } from 'vue'

import { defineStore } from 'pinia'

import { TTLValues, useLocalStorageCache } from '~resources/lib/locale-storage-cache/locale-storage-cache'

import { CityResponse, useCitySearchAPI } from '~api/city'

export const useCityStore = defineStore('city', () => {
  const { hasData, setData, getData } = useLocalStorageCache('cities', TTLValues.DAY)
  const { data: cities, execute: fetchCities } = useCitySearchAPI({})
  onMounted(async () => {
    if (hasData()) {
      cities.value = getData() as CityResponse[]
    } else {
      await fetchCities()
      setData(cities.value)
    }
  })
  return {
    cities,
  }
})
