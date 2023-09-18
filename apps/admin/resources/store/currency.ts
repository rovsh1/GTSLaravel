import { onMounted } from 'vue'

import { defineStore } from 'pinia'

import { useCurrencyGetAPI } from '~api/currency'

export const useCurrencyStore = defineStore('currency', () => {
  const { data: currencies, execute: fetchCurrencies } = useCurrencyGetAPI()

  const getCurrencyByCodeChar = (currencyCode: string | undefined) => currencies.value?.find((cur) => currencyCode === cur.code_char)

  onMounted(() => fetchCurrencies())

  return {
    currencies,
    getCurrencyByCodeChar,
  }
})
