import { onMounted } from 'vue'

import { defineStore } from 'pinia'

import { Currency } from '~resources/api/models'
import useLocalStorageCache from '~resources/lib/locale-storage-cache/locale-storage-cache'

import { useCurrencyGetAPI } from '~api/currency'

export const useCurrencyStore = defineStore('currency', () => {
  const { data: dataFromStorage, existData, saveToLocalStorage } = useLocalStorageCache('currencies')
  const { data: currencies, execute: fetchCurrencies } = useCurrencyGetAPI()

  const getCurrencyByCodeChar = (currencyCode: string | undefined) => currencies.value?.find((cur) => currencyCode === cur.code_char)

  onMounted(async () => {
    if (existData.value) {
      currencies.value = dataFromStorage.value as Currency[]
    } else {
      await fetchCurrencies()
      saveToLocalStorage(currencies.value)
    }
  })

  return {
    currencies,
    getCurrencyByCodeChar,
  }
})
