import { onMounted } from 'vue'

import { defineStore } from 'pinia'

import { useCitySearchAPI } from '~api/city'

export const useCityStore = defineStore('city', () => {
  const { data: cities, execute: fetchCities } = useCitySearchAPI({})
  onMounted(() => fetchCities())
  return {
    cities,
  }
})
