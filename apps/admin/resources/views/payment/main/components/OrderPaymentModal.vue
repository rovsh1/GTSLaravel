<script setup lang="ts">

import { computed, ref } from 'vue'

import { useToggle } from '@vueuse/core'

import OrderPaymentsTable from '~resources/views/payment/main/components/OrderPaymentsTable.vue'

import { usePaymentOrdersListAPI, usePaymentWaitingOrdersListAPI } from '~api/payment/payment'

import { useApplicationEventBus } from '~lib/event-bus'

import BaseDialog from '~components/BaseDialog.vue'
import OverlayLoading from '~components/OverlayLoading.vue'

const eventBus = useApplicationEventBus()
const [isOpened, toggleModal] = useToggle()
const paymentID = ref<number>()

const paymentIdProps = computed(() => (paymentID.value ? { paymentID: paymentID.value } : null))

const { isFetching: isFetchingWaitingOrders,
  execute: fetchWaitingOrders, data: waitingOrders } = usePaymentWaitingOrdersListAPI(paymentIdProps)
const { isFetching: isFetchingOrders, execute: fetchOrders, data: orders } = usePaymentOrdersListAPI(paymentIdProps)

eventBus.on('openOrderPaymentModal', (event: { paymentId: number }) => {
  paymentID.value = event.paymentId
  toggleModal(true)
  fetchWaitingOrders()
  fetchOrders()
})

const closeModal = () => {
  paymentID.value = undefined
  toggleModal(false)
}

const onSubmit = () => {
  console.log('onSubmit', paymentID.value)
  closeModal()
  window.location.reload()
}

</script>

<template>
  <BaseDialog :auto-width="true" :opened="isOpened as boolean" @close="toggleModal(false)">
    <template #title>Распределение оплат</template>
    <div class="position-relative">
      <OverlayLoading v-if="isFetchingWaitingOrders || isFetchingOrders" />
      <OrderPaymentsTable
        :waiting-orders="waitingOrders || []"
        :orders="orders || []"
        :remaining-amount="null"
        :loading="isFetchingWaitingOrders || isFetchingOrders || (!waitingOrders?.length && !orders?.length)"
        @orders="() => {}"
      />
    </div>
    <template #actions-end>
      <button
        class="btn btn-primary"
        type="button"
        :disabled="isFetchingWaitingOrders || isFetchingOrders
          || (!waitingOrders?.length && !orders?.length)"
        @click="onSubmit"
      >
        Сохранить
      </button>
      <button class="btn btn-cancel" type="button" @click="closeModal">Отмена</button>
    </template>
  </BaseDialog>
</template>
