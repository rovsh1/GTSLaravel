<script setup lang="ts">
import { computed } from 'vue'

import RequestBlock from '~resources/views/booking/shared/components/RequestBlock.vue'
import { useOrderStore } from '~resources/views/booking-order/show/store/order'
import { useBookingVoucherStore } from '~resources/views/booking-order/show/store/voucher'

import { OrderAvailableActionsResponse } from '~api/order/status'
import { OrderVoucher } from '~api/order/voucher'

import { formatDateTime } from '~lib/date'

const orderStore = useOrderStore()
const { fetchOrder, fetchAvailableActions } = orderStore
const availableActions = computed<OrderAvailableActionsResponse | null>(() => orderStore.availableActions)
const isRequestableStatus = computed<boolean>(() => true) // availableActions.value?.isRequestable

const voucherStore = useBookingVoucherStore()
const orderVouchers = computed<OrderVoucher[] | null>(() => voucherStore.vouchers)

const isVoucherFetching = computed(() => voucherStore.voucherSendIsFetching)

const canSendVoucher = computed<boolean>(() => availableActions.value?.canSendVoucher || false)

const handleVoucherSend = async () => {
  await voucherStore.sendVoucher()
  await Promise.all([
    fetchAvailableActions(),
    fetchOrder(),
  ])
}
</script>

<template>
  <div class="reservation-requests">
    <div
      v-for="orderVoucher in orderVouchers"
      :key="orderVoucher.id"
      class="d-flex flex-row justify-content-between w-100 py-1"
    >
      <div>
        Ваучер
        <span class="date align-left ml-1"> от
          {{ formatDateTime(orderVoucher.dateCreate) }}</span>
      </div>
      <a href="#" class="btn-download" @click.prevent="voucherStore.downloadDocument(orderVoucher.id)">Скачать</a>
    </div>
  </div>

  <div v-if="isRequestableStatus" :class="{ 'mt-2': orderVouchers?.length }">
    <RequestBlock
      v-if="canSendVoucher"
      text="При необходимости клиенту можно отправить ваучер"
      button-text="Отправить ваучер"
      variant="success"
      :loading="isVoucherFetching"
      @click="handleVoucherSend"
    />
  </div>
  <div v-if="!isRequestableStatus && !orderVouchers?.length">
    <RequestBlock
      :show-button="false"
      text="Ваучеры клиенту не отправлялись"
    />
  </div>
</template>
