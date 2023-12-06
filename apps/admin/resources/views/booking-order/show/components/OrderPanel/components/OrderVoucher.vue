<script setup lang="ts">
import { computed } from 'vue'

import RequestBlock from '~resources/views/booking/shared/components/RequestBlock.vue'
import { useOrderStore } from '~resources/views/booking-order/show/store/order'

import { OrderAvailableActionsResponse } from '~api/order/status'
import { OrderVoucher } from '~api/order/voucher'

import { formatDateTime } from '~lib/date'
import { downloadFile } from '~lib/download-file'

import { showToast } from '~components/Bootstrap/BootstrapToast'
import InlineIcon from '~components/InlineIcon.vue'

const orderStore = useOrderStore()
const availableActions = computed<OrderAvailableActionsResponse | null>(() => orderStore.availableActions)

const orderVoucher = computed<OrderVoucher | null>(() => orderStore.order?.voucher || null)

const download = async (): Promise<void> => {
  if (!orderVoucher.value?.file) {
    return
  }
  await downloadFile(orderVoucher.value.file.url, orderVoucher.value.file.name)
}

const handleVoucherCreate = async () => {
  await orderStore.createVoucher()
}

const handleVoucherSend = async () => {
  try {
    await orderStore.sendVoucher()
    showToast({ title: 'Ваучер успешно отправлен' }, {
      type: 'success',
    })
  } catch (error) {
    await orderStore.refreshOrder()
  }
}
</script>

<template>
  <div class="order-voucher">
    <div v-if="orderVoucher" class="d-flex flex-row justify-content-between w-100 py-1">
      <div class="d-flex align-items-center">
        <div>
          Ваучер
          <span class="date align-left ml-1"> от {{ formatDateTime(orderVoucher.createdAt) }}</span>
        </div>
      </div>
      <div class="d-flex gap-2">
        <a
          v-if="availableActions?.canSendVoucher"
          v-tooltip="'Отправить'"
          href="#"
          class="btn-download"
          aria-label="Отправить"
          @click.prevent="handleVoucherSend"
        >
          <InlineIcon icon="mail" />
        </a>
        <a
          v-tooltip="'Скачать'"
          href="#"
          class="btn-download"
          aria-label="Скачать"
          @click.prevent="download"
        >
          <InlineIcon icon="download" />
        </a>
      </div>
    </div>
  </div>

  <div v-if="!orderVoucher">
    <RequestBlock
      v-if="availableActions?.canCreateVoucher"
      text="При необходимости клиенту можно сформировать ваучер"
      button-text="Сформировать ваучер"
      variant="success"
      :loading="orderStore.isVoucherFetching"
      @click="handleVoucherCreate"
    />
    <RequestBlock
      v-else
      :show-button="false"
      text="Нет сформированных ваучеров"
    />
  </div>
</template>
