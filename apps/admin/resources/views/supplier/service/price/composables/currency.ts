import { computed, ref } from 'vue'

import { defineStore } from 'pinia'

import { mapEntitiesToSelectOptions } from '~resources/views/booking/lib/constants'

import { Currency } from '~api/models'

export const useCurrenciesStore = defineStore('currencies', () => {
  const defaultCurrencyId = 1
  const currencies = ref<Currency[]>([])
  const currencySelectOptions = computed(() => mapEntitiesToSelectOptions(currencies.value))

  const getCurrencyChar = (id: number) => currencies.value.find((currency) => currency.id === id)?.code_char

  const setCurrencies = (data: Currency[]) => {
    currencies.value = data
  }

  const isDefaultCurrency = (currencyId: number) => currencyId === defaultCurrencyId

  return {
    defaultCurrencyId,
    currencies,
    currencySelectOptions,
    getCurrencyChar,
    setCurrencies,
    isDefaultCurrency,
  }
})
