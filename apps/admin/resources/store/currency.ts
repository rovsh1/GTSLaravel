import { onMounted } from 'vue'

import { defineStore } from 'pinia'

import { useCurrencyGetAPI } from '~api/currency'

export const useCurrencyStore = defineStore('currency', () => {
  const { data: currencies, execute: fetchCurrencies } = useCurrencyGetAPI()

  onMounted(() => fetchCurrencies())

  return {
    currencies,
  }
})
