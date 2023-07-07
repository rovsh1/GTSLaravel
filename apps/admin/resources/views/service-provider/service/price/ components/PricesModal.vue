<script setup lang="ts">

import { computed, ref, watchEffect } from 'vue'

import { MaybeRef } from '@vueuse/core'

import { useCurrenciesStore } from '~resources/views/service-provider/service/price/composables/currency'

import { Money } from '~api/models'
import { ServicePriceResponse as AirportServicePriceResponse } from '~api/service-provider/airport'
import { ServicePriceResponse as TransferServicePriceResponse } from '~api/service-provider/transfer'

import BaseDialog from '~components/BaseDialog.vue'
import BootstrapSelectBase from '~components/Bootstrap/BootstrapSelectBase.vue'
import SmallDeleteButton from '~components/SmallDeleteButton.vue'

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
const { getCurrencyChar } = currenciesStore
const currencySelectOptions = computed(() => currenciesStore.currencySelectOptions)

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
  grossPrices.value = [{ amount: undefined, currency_id: 1 }]
})

</script>

<template>
  <BaseDialog
    :opened="opened as boolean"
    :loading="loading"
    @close="$emit('close')"
    @keyup.enter="submit"
  >
    <template #title>{{ header || 'Стоимость услуги' }}</template>

    //@todo сделать таблицу валюта-стоимость
    <form ref="modalPricesForm" class="row g-3">
      <div class="col-md-12 field-required">
        <label for="net-price">Нетто (UZS)</label>
        <input id="net-price" v-model.number="netPrice" type="number" class="form-control" required>
      </div>

      <template v-for="(price, idx) in grossPrices" :key="idx">
        <div class="col-md-6 field-required">
          <label :for="`brutto-price-${idx}`">
            Брутто <template v-if="price.currency_id">({{ getCurrencyChar(price.currency_id) }})</template>
          </label>
          <input :id="`brutto-price-${idx}`" v-model.number="price.amount" type="number" class="form-control" required>
        </div>
        <div class="col-md-5 field-required">
          <label :for="`brutto-currency-${idx}`">Валюта</label>
          <BootstrapSelectBase
            :id="`brutto-currency-${idx}`"
            :options="currencySelectOptions"
            :value="price.currency_id"
            required
            @input="value => price.currency_id = Number(value)"
          />
        </div>
        <div class="col-md-1 pt-4">
          <SmallDeleteButton @click.prevent="grossPrices.splice(idx, 1)" />
        </div>
      </template>

      <div class="col-md-12">
        <a href="#" class="btn btn-add" @click.prevent="grossPrices.push({})">
          <i class="icon">add</i>
          Добавить цену брутто
        </a>
      </div>
    </form>

    <template #actions-end>
      <button class="btn btn-primary" type="button" @click="submit">Сохранить</button>
      <button class="btn btn-cancel" type="button" @click="$emit('close')">Отмена</button>
    </template>
  </BaseDialog>
</template>
