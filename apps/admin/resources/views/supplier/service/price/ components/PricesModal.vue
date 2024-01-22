<script setup lang="ts">

import { computed, ref, watchEffect } from 'vue'

import { MaybeRef } from '@vueuse/core'

import { useCurrenciesStore } from '~resources/views/supplier/service/price/composables/currency'

import { Money } from '~api/models'
import { ServicePriceResponse as AirportServicePriceResponse } from '~api/supplier/airport'
import { ServicePriceResponse as TransferServicePriceResponse } from '~api/supplier/transfer'

import BaseDialog from '~components/BaseDialog.vue'

const props = defineProps<{
  opened: MaybeRef<boolean>
  loading: MaybeRef<boolean>
  header?: string
  servicePrice?: AirportServicePriceResponse | TransferServicePriceResponse
}>()

const emit = defineEmits<{
  (event: 'close'): void
  (event: 'submit', netPrice?: number, grossPrices?: Money[]): void
}>()

const currenciesStore = useCurrenciesStore()
const { getCurrencyChar, isDefaultCurrency, defaultCurrency } = currenciesStore
const currencies = computed(() => currenciesStore.currencies)

const isLoading = computed(() => Boolean(props.loading))

const netPrice = ref<number>()
const grossPrices = ref<Money[]>([])

const modalPricesForm = ref()
const submit = () => {
  if (!modalPricesForm.value.reportValidity() || isLoading.value) {
    return
  }
  emit('submit', netPrice.value, grossPrices.value)
}

watchEffect(() => {
  if (props.servicePrice?.prices_gross && props.servicePrice.price_net) {
    netPrice.value = props.servicePrice.price_net
    grossPrices.value = props.servicePrice.prices_gross
    return
  }

  netPrice.value = undefined
  grossPrices.value = [{ amount: undefined, currency: defaultCurrency }]
})

const getGrossPriceLabel = (currencyId?: number): string => {
  if (!currencyId) {
    return ''
  }
  const currencyChar = getCurrencyChar(currencyId)
  if (!currencyChar) {
    return ''
  }
  return `Брутто (${currencyChar})`
}

const handleChangeGrossPrice = (currency: string, event: any) => {
  const amount = Number(event.target.value)
  const grossPrice = grossPrices.value.find((price) => price.currency === currency)
  if (grossPrice) {
    grossPrice.amount = amount
    return
  }
  grossPrices.value.push({ amount, currency })
}

const getGrossPriceAmountByCurrency = (currency: string) => (
  grossPrices.value.find((price) => price.currency === currency)?.amount
)

</script>

<template>
  <BaseDialog
    :opened="opened as boolean"
    :loading="loading"
    @close="$emit('close')"
    @keydown.enter="submit"
  >
    <template #title>{{ header || 'Стоимость услуги' }}</template>

    <form ref="modalPricesForm" class="row g-3">
      <div class="col-md-12 field-required">
        <label for="net-price">Нетто (UZS)</label>
        <input id="net-price" v-model.number="netPrice" type="number" class="form-control" required>
      </div>

      <template v-for="currency in currencies" :key="currency.id">
        <div class="col-md-12" :class="{ 'field-required': isDefaultCurrency(currency.code_char) }">
          <label :for="`brutto-price-${currency.id}`">
            {{ getGrossPriceLabel(currency.id) }}
          </label>
          <input
            :id="`brutto-price-${currency.id}`"
            type="number"
            class="form-control"
            :required="isDefaultCurrency(currency.code_char)"
            :value="getGrossPriceAmountByCurrency(currency.code_char)"
            @input="handleChangeGrossPrice(currency.code_char, $event)"
          >
        </div>
      </template>
    </form>

    <template #actions-end>
      <button class="btn btn-primary" type="button" :disabled="isLoading" @click="submit">Сохранить</button>
      <button class="btn btn-cancel" type="button" :disabled="isLoading" @click="$emit('close')">Отмена</button>
    </template>
  </BaseDialog>
</template>
