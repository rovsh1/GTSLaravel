import { onMounted, ref } from 'vue'

import { defineStore } from 'pinia'

import { Currency } from '~resources/api/models'
import { CacheStorage } from '~resources/lib/cache-storage/cache-storage'
import { TTLValues } from '~resources/lib/enums'

import { useCurrencyGetAPI } from '~api/currency'

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
