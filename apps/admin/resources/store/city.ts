import { onMounted } from 'vue'

import { defineStore } from 'pinia'

import useLocalStorageCache from '~resources/lib/locale-storage-cache/locale-storage-cache'

import { CityResponse, useCitySearchAPI } from '~api/city'

export const useCityStore = defineStore('city', () => {
  const { data: dataFromStorage, existData, saveToLocalStorage } = useLocalStorageCache('cities')
  const { data: cities, execute: fetchCities } = useCitySearchAPI({})
  onMounted(async () => {
    if (existData.value) {
      cities.value = dataFromStorage.value as CityResponse[]
    } else {
      await fetchCities()
      saveToLocalStorage(cities.value)
    }
  })
  return {
    cities,
  }
})
