import { onMounted } from 'vue'

import { defineStore } from 'pinia'

import { CityResponse, useCitySearchAPI } from '~api/city'

import useLocalStorageCache from '~lib/locale-storage-cache/index'

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
