import { onMounted } from 'vue'

import { defineStore } from 'pinia'

import { Currency } from '~resources/api/models'
import { TTLValues, useLocalStorageCache } from '~resources/lib/locale-storage-cache/locale-storage-cache'

import { useCurrencyGetAPI } from '~api/currency'

export const useCurrencyStore = defineStore('currency', () => {
  const { hasData, setData, getData } = useLocalStorageCache('currencies', TTLValues.DAY)
  const { data: currencies, execute: fetchCurrencies } = useCurrencyGetAPI()

  const getCurrencyByCodeChar = (currencyCode: string | undefined) => currencies.value?.find((cur) => currencyCode === cur.code_char)

  onMounted(async () => {
    if (hasData()) {
      currencies.value = getData() as Currency[]
    } else {
      await fetchCurrencies()
      setData(currencies.value)
    }
  })

  return {
    currencies,
    getCurrencyByCodeChar,
  }
})
