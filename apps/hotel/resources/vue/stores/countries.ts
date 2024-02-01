import { onMounted, ref } from 'vue'

import { defineStore } from 'pinia'

import { CountryResponse, useCountrySearchAPI } from '~api/country'

import { CacheStorage } from '~cache/cache-storage'
import { TTLValues } from '~cache/enums'

export const useCountryStore = defineStore('country', () => {
  const countries = ref<CountryResponse[] | null>(null)
  onMounted(async () => {
    countries.value = await CacheStorage.remember('countries', TTLValues.DAY, async () => {
      const { data, execute: fetchCountries } = useCountrySearchAPI()
      await fetchCountries()
      return data.value
    }) as CountryResponse[]
  })

  return {
    countries,
  }
})
