<script setup lang="ts">
import { computed } from 'vue'

import { formatDateTime } from 'gts-common/helpers/date'
import { downloadFile } from 'gts-common/helpers/download-file'
import InlineIcon from 'gts-components/Base/InlineIcon'
import { showToast } from 'gts-components/Bootstrap/BootstrapToast/index'

import { useOrderStore } from '~resources/views/booking-order/show/store/order'

import { OrderAvailableActionsResponse } from '~api/order/status'
import { OrderVoucher } from '~api/order/voucher'

const orderStore = useOrderStore()
const availableActions = computed<OrderAvailableActionsResponse | null>(() => orderStore.availableActions)

const orderVoucher = computed<OrderVoucher | null>(() => orderStore.order?.voucher || null)

const handleDownload = async (): Promise<void> => {
  const voucher = await orderStore.createVoucher()
  if (!voucher.value.file) {
    return
  }
  await downloadFile(voucher.value.file.url, voucher.value.file.name)
}

const handleOpen = async () => {
  const voucher = await orderStore.createVoucher()
  if (!voucher.value.file) {
    return
  }
  window.open(voucher.value.file.url, 'blank')
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
    <div class="d-flex flex-row justify-content-between w-100 py-1">
      <div class="d-flex align-items-center">
        <div>
          Ваучер
          <span v-if="orderVoucher?.sendAt" class="date align-left ml-1"> отправлен клиенту {{ formatDateTime(orderVoucher?.sendAt) }}</span>
          <span v-else class="date align-left ml-1"> не отправялся клиенту</span>
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
          v-if="availableActions?.canCreateVoucher"
          v-tooltip="'Скачать'"
          href="#"
          class="btn-download"
          aria-label="Скачать"
          @click.prevent="handleDownload"
        >
          <InlineIcon icon="download" />
        </a>
        <a
          v-if="availableActions?.canCreateVoucher"
          v-tooltip="'Открыть'"
          href="#"
          class="btn-download"
          aria-label="Открыть"
          @click.prevent="handleOpen"
        >
          <InlineIcon icon="open_in_new" />
        </a>
      </div>
    </div>
  </div>
</template>
