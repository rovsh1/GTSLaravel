<script setup lang="ts">
import { computed } from 'vue'

import RequestBlock from '~resources/views/booking/shared/components/RequestBlock.vue'
import { useOrderInvoiceStore } from '~resources/views/booking-order/show/store/invoice'
import { useOrderStore } from '~resources/views/booking-order/show/store/order'

import { OrderInvoice } from '~api/order/invoice'
import { OrderAvailableActionsResponse } from '~api/order/status'

import { showToast } from '~components/Bootstrap/BootstrapToast'
import InlineIcon from '~components/InlineIcon.vue'

import { formatDateTime } from '~helpers/date'

const orderStore = useOrderStore()
const availableActions = computed<OrderAvailableActionsResponse | null>(() => orderStore.availableActions)

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
  try {
    await invoiceStore.sendInvoice()
    showToast({ title: 'Инвойс успешно отправлен' }, {
      type: 'success',
    })
  } catch (error) {
    await orderStore.refreshOrder()
    return
  }
  await orderStore.refreshOrder()
}
</script>

<template>
  <div class="order-invoice">
    <div v-if="orderInvoice" class="d-flex flex-row justify-content-between w-100 py-1">
      <div class="d-flex align-items-center">
        <div>
          Инвойс
          <span class="date align-left ml-1"> от {{ formatDateTime(orderInvoice.createdAt) }}</span>
          <span v-if="orderInvoice?.sendAt" class="date align-left ml-1"> отправлен клиенту {{ formatDateTime(orderInvoice?.sendAt) }}</span>
          <span v-else class="date align-left ml-1"> не отправялся клиенту</span>
        </div>
      </div>
      <div class="d-flex gap-2">
        <a
          v-if="availableActions?.canSendInvoice"
          v-tooltip="'Отправить'"
          href="#"
          class="btn-download"
          aria-label="Отправить"
          @click.prevent="handleInvoiceSend"
        >
          <InlineIcon icon="mail" />
        </a>
        <a
          v-tooltip="'Скачать'"
          href="#"
          class="btn-download"
          aria-label="Скачать"
          @click.prevent="invoiceStore.downloadFile()"
        >
          <InlineIcon icon="download" />
        </a>
        <a
          v-if="availableActions?.canCancelInvoice"
          v-tooltip="'Отменить'"
          href="#"
          class="btn-download"
          aria-label="Отменить"
          @click.prevent="handleCancelInvoice"
        >
          <InlineIcon icon="delete" />
        </a>
      </div>
    </div>
  </div>

  <div v-if="!orderInvoice">
    <RequestBlock
      v-if="availableActions?.canCreateInvoice"
      text="При необходимости клиенту можно сформировать инвойс"
      button-text="Сформировать инвойс"
      variant="success"
      :loading="isFetching"
      @click="handleInvoiceCreate"
    />
    <RequestBlock
      v-else
      :show-button="false"
      text="Нет сформированных инвойсов"
    />
  </div>
</template>
