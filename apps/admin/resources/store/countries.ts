import { onMounted } from 'vue'

import { defineStore } from 'pinia'

import { CountryResponse, useCountrySearchAPI } from '~api/country'

import useLocalStorageCache from '~lib/locale-storage-cache/index'

export const useCountryStore = defineStore('country', () => {
  const { data: dataFromStorage, existData, saveToLocalStorage } = useLocalStorageCache('countries')
  const { data: countries, execute: fetchCountries } = useCountrySearchAPI()
  onMounted(async () => {
    if (existData.value) {
      countries.value = dataFromStorage.value as CountryResponse[]
    } else {
      await fetchCountries()
      saveToLocalStorage(countries.value)
    }
  })

  return {
    countries,
  }
})
