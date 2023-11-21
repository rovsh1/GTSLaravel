<script setup lang="ts">
import { computed } from 'vue'

import RequestBlock from '~resources/views/booking/shared/components/RequestBlock.vue'
import { useOrderInvoiceStore } from '~resources/views/booking-order/show/store/invoice'
import { useOrderStore } from '~resources/views/booking-order/show/store/order'

import { OrderInvoice } from '~api/order/invoice'
import { OrderAvailableActionsResponse } from '~api/order/status'

import { formatDateTime } from '~lib/date'

const orderStore = useOrderStore()
const availableActions = computed<OrderAvailableActionsResponse | null>(() => orderStore.availableActions)
const isRequestableStatus = computed<boolean>(() => true) // availableActions.value?.isRequestable

const invoiceStore = useOrderInvoiceStore()

const orderInvoice = computed<OrderInvoice | null>(
  () => (invoiceStore.invoice && Object.keys(invoiceStore.invoice).length > 0 ? invoiceStore.invoice : null),
)
const isFetching = computed(() => invoiceStore.invoiceCreateIsFetching || invoiceStore.isFetching)

const handleInvoiceCreate = async () => {
  await invoiceStore.createInvoice()
  await orderStore.refreshOrder()
}

const handleCancelInvoice = async () => {
  await invoiceStore.cancelInvoice()
  await orderStore.refreshOrder()
}

const handleInvoiceSend = async () => {
  await invoiceStore.sendInvoice()
  await orderStore.refreshOrder()
}
</script>

<template>
  <div class="order-invoice">
    <div v-if="orderInvoice" class="d-flex flex-row justify-content-between w-100 py-1">
      <div>
        Инвойс
        <span class="date align-left ml-1"> от {{ formatDateTime(orderInvoice.createdAt) }}</span>
      </div>
      <div class="flex gap-2">
        <a href="#" class="btn-download" @click.prevent="invoiceStore.downloadFile()">Скачать</a>
        <a v-if="availableActions?.canSendInvoice" href="#" class="btn-download" @click.prevent="handleInvoiceSend">Отправить</a>
        <a v-if="availableActions?.canCancelInvoice" href="#" class="btn-download" @click.prevent="handleCancelInvoice">Удалить</a>
      </div>
    </div>
  </div>

  <div v-if="isRequestableStatus && !orderInvoice" class="mt-2">
    <RequestBlock
      v-if="availableActions?.canCreateInvoice"
      text="При необходимости клиенту можно сформировать инвойс"
      button-text="Сформировать инвойс"
      variant="success"
      :loading="isFetching"
      @click="handleInvoiceCreate"
    />
  </div>
  <div v-if="!isRequestableStatus && !orderInvoice">
    <RequestBlock
      :show-button="false"
      text="Инвоисов нет"
    />
  </div>
</template>
