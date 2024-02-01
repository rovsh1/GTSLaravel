import { onMounted, ref } from 'vue'

import { defineStore } from 'pinia'

import { useCurrencyGetAPI } from '~api/currency'
import { Currency } from '~api/models'

import { CacheStorage } from '~cache/cache-storage'
import { TTLValues } from '~cache/enums'

export const useCurrencyStore = defineStore('currency', () => {
  const currencies = ref<Currency[] | null>(null)

  const getCurrencyByCodeChar = (currencyCode: string | undefined) => currencies.value?.find((cur) => currencyCode === cur.code_char)

  onMounted(async () => {
    currencies.value = await CacheStorage.remember('currencies', TTLValues.DAY, async () => {
      const { data, execute: fetchCurrencies } = useCurrencyGetAPI()
      await fetchCurrencies()
      return data.value
    }) as Currency[]
  })

  return {
    currencies,
    getCurrencyByCodeChar,
  }
})
