<script setup lang="ts">
import { computed } from 'vue'

import RequestBlock from '~resources/views/booking/shared/components/RequestBlock.vue'
import { useOrderStore } from '~resources/views/booking-order/show/store/order'
import { useOrderVoucherStore } from '~resources/views/booking-order/show/store/voucher'

import { OrderAvailableActionsResponse } from '~api/order/status'
import { OrderVoucher } from '~api/order/voucher'

import { formatDateTime } from '~lib/date'

import { showToast } from '~components/Bootstrap/BootstrapToast'
import InlineIcon from '~components/InlineIcon.vue'

const orderStore = useOrderStore()
const availableActions = computed<OrderAvailableActionsResponse | null>(() => orderStore.availableActions)

const voucherStore = useOrderVoucherStore()

const orderVoucher = computed<OrderVoucher | null>(
  () => (voucherStore.voucher && Object.keys(voucherStore.voucher).length > 0 ? voucherStore.voucher : null),
)
const isFetching = computed(() => voucherStore.voucherCreateIsFetching || voucherStore.isFetching)

const handleVoucherCreate = async () => {
  await voucherStore.createVoucher()
  await orderStore.refreshOrder()
}

const handleVoucherSend = async () => {
  try {
    await voucherStore.sendVoucher()
    showToast({ title: 'Ваучер успешно отправлен' }, {
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
          @click.prevent="voucherStore.downloadFile()"
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
      :loading="isFetching"
      @click="handleVoucherCreate"
    />
    <RequestBlock
      v-else
      :show-button="false"
      text="Нет сформированных ваучеров"
    />
  </div>
</template>
