import { onMounted } from 'vue'

import { defineStore } from 'pinia'

import { TTLValues, useLocalStorageCache } from '~resources/lib/locale-storage-cache/locale-storage-cache'

import { CountryResponse, useCountrySearchAPI } from '~api/country'

export const useCountryStore = defineStore('country', () => {
  const { hasData, setData, getData } = useLocalStorageCache('countries', TTLValues.DAY)
  const { data: countries, execute: fetchCountries } = useCountrySearchAPI()
  onMounted(async () => {
    if (hasData()) {
      countries.value = getData() as CountryResponse[]
    } else {
      await fetchCountries()
      setData(countries.value)
    }
  })

  return {
    countries,
  }
})
