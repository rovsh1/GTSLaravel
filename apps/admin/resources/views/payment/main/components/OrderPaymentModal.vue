<script setup lang="ts">

import { computed, ref } from 'vue'

import { useToggle } from '@vueuse/core'

import OrderPaymentsTable from '~resources/views/payment/main/components/OrderPaymentsTable.vue'

import { ordersLend, PaymentOrderPayload, useGetPaymentAPI,
  usePaymentOrdersListAPI, usePaymentWaitingOrdersListAPI } from '~api/payment/payment'

import { useApplicationEventBus } from '~lib/event-bus'

import BaseDialog from '~components/BaseDialog.vue'
import OverlayLoading from '~components/OverlayLoading.vue'

const eventBus = useApplicationEventBus()
const [isOpened, toggleModal] = useToggle()
const paymentID = ref<number>()

const newDistributedOrders = ref<PaymentOrderPayload[]>([])

const paymentIdProps = computed(() => (paymentID.value ? { paymentID: paymentID.value } : null))

const { isFetching: isFetchingWaitingOrders,
  execute: fetchWaitingOrders, data: waitingOrders } = usePaymentWaitingOrdersListAPI(paymentIdProps)
const { isFetching: isFetchingOrders, execute: fetchOrders, data: orders } = usePaymentOrdersListAPI(paymentIdProps)
const { isFetching: isFetchingPayment, execute: fetchPayment, data: payment } = useGetPaymentAPI(paymentIdProps)

const isFetching = computed(() => (isFetchingWaitingOrders.value || isFetchingOrders.value || isFetchingPayment.value))

const isDisabled = computed(() => (!waitingOrders.value?.length && !orders.value?.length)
|| !payment.value?.remainingAmount)

eventBus.on('openOrderPaymentModal', (event: { paymentId: number }) => {
  paymentID.value = event.paymentId
  toggleModal(true)
  fetchPayment()
  fetchWaitingOrders()
  fetchOrders()
})

const closeModal = () => {
  paymentID.value = undefined
  newDistributedOrders.value = []
  toggleModal(false)
}

const onSubmit = async () => {
  if (!paymentID.value) return
  await ordersLend({
    paymentID: paymentID.value,
    orders: newDistributedOrders.value,
  })
  closeModal()
  // window.location.reload()
}

</script>

<template>
  <BaseDialog :auto-width="true" :opened="isOpened as boolean" @close="toggleModal(false)">
    <template #title>Распределение оплат</template>
    <div class="position-relative">
      <OverlayLoading v-if="isFetching" />
      <OrderPaymentsTable
        :waiting-orders="waitingOrders || []"
        :orders="orders || []"
        :remaining-amount="payment?.remainingAmount"
        :loading="isFetching"
        :disabled="isDisabled"
        @orders="(orders) => newDistributedOrders = orders"
      />
    </div>
    <template #actions-end>
      <button
        class="btn btn-primary"
        type="button"
        :disabled="isFetching || isDisabled"
        @click="onSubmit"
      >
        Сохранить
      </button>
      <button class="btn btn-cancel" type="button" @click="closeModal">Отмена</button>
    </template>
  </BaseDialog>
</template>
