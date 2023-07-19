<script setup lang="ts">

import { computed, ref, watchEffect } from 'vue'

import { MaybeRef } from '@vueuse/core'

import { useCurrenciesStore } from '~resources/views/service-provider/service/price/composables/currency'

import { Money } from '~api/models'
import { ServicePriceResponse as AirportServicePriceResponse } from '~api/service-provider/airport'
import { ServicePriceResponse as TransferServicePriceResponse } from '~api/service-provider/transfer'

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
const { getCurrencyChar, isDefaultCurrency, defaultCurrencyId } = currenciesStore
const currencies = computed(() => currenciesStore.currencies)

const netPrice = ref<number>()
const grossPrices = ref<Money[]>([])

const modalPricesForm = ref()
const submit = () => {
  if (!modalPricesForm.value.reportValidity()) {
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
  grossPrices.value = [{ amount: undefined, currency_id: defaultCurrencyId }]
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

const handleChangeGrossPrice = (currencyId: number, event: any) => {
  const amount = Number(event.target.value)
  const grossPrice = grossPrices.value.find((price) => price.currency_id === currencyId)
  if (grossPrice) {
    grossPrice.amount = amount
    return
  }
  grossPrices.value.push({ amount, currency_id: currencyId })
}

const getGrossPriceAmountByCurrency = (currencyId: number) => (
  grossPrices.value.find((price) => price.currency_id === currencyId)?.amount
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
        <div class="col-md-12" :class="{ 'field-required': isDefaultCurrency(currency.id) }">
          <label :for="`brutto-price-${currency.id}`">
            {{ getGrossPriceLabel(currency.id) }}
          </label>
          <input
            :id="`brutto-price-${currency.id}`"
            type="number"
            class="form-control"
            :required="isDefaultCurrency(currency.id)"
            :value="getGrossPriceAmountByCurrency(currency.id)"
            @input="handleChangeGrossPrice(currency.id, $event)"
          >
        </div>
      </template>
    </form>

    <template #actions-end>
      <button class="btn btn-primary" type="button" @click="submit">Сохранить</button>
      <button class="btn btn-cancel" type="button" @click="$emit('close')">Отмена</button>
    </template>
  </BaseDialog>
</template>
