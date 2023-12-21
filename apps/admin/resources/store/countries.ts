import { onMounted, ref } from 'vue'

import { defineStore } from 'pinia'

import { CacheStorage } from '~resources/lib/cache-storage/cache-storage'
import { TTLValues } from '~resources/lib/enums'

import { CountryResponse, useCountrySearchAPI } from '~api/country'

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
