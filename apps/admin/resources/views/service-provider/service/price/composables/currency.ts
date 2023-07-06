import { computed, ref } from 'vue'

import { defineStore } from 'pinia'

import { mapEntitiesToSelectOptions } from '~resources/views/hotel-booking/show/constants'

import { Currency } from '~api/models'

export const useCurrenciesStore = defineStore('currencies', () => {
  const currencies = ref<Currency[]>([])
  const currencySelectOptions = computed(() => mapEntitiesToSelectOptions(currencies.value))

  const getCurrencyChar = (id: number) => currencies.value.find((currency) => currency.id === id)?.code_char

  const setCurrencies = (data: Currency[]) => {
    currencies.value = data
  }

  return {
    currencies,
    currencySelectOptions,
    getCurrencyChar,
    setCurrencies,
  }
})
