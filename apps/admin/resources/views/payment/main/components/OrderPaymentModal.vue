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
const { isFetching: isFetchingDistributedOrders,
  execute: fetchDistributedOrders, data: distributedOrders } = usePaymentOrdersListAPI(paymentIdProps)
const { isFetching: isFetchingPayment, execute: fetchPayment, data: payment } = useGetPaymentAPI(paymentIdProps)

const isSendingOrders = ref<boolean>(false)

const isFetching = computed(() => (isFetchingWaitingOrders.value || isFetchingDistributedOrders.value || isFetchingPayment.value))

const isDisabled = computed(() => (!waitingOrders.value?.length && !distributedOrders.value?.length)
|| !payment.value?.remainingAmount)

eventBus.on('openOrderPaymentModal', (event: { paymentId: number }) => {
  paymentID.value = event.paymentId
  toggleModal(true)
  fetchPayment()
  fetchWaitingOrders()
  fetchDistributedOrders()
})

const closeModal = () => {
  paymentID.value = undefined
  newDistributedOrders.value = []
  toggleModal(false)
}

const onSubmit = async () => {
  if (!paymentID.value) return
  isSendingOrders.value = true
  const response = await ordersLend({
    paymentID: paymentID.value,
    orders: newDistributedOrders.value,
  })
  isSendingOrders.value = false
  if (response.data.value?.success) {
    closeModal()
    window.location.reload()
  }
}

</script>

<template>
  <BaseDialog :auto-width="true" :opened="isOpened as boolean" @close="toggleModal(false)">
    <template #title>Распределение оплат</template>
    <div class="position-relative">
      <OverlayLoading v-if="isFetching || isSendingOrders" />
      <OrderPaymentsTable
        :waiting-orders="waitingOrders || []"
        :distributed-orders="distributedOrders || []"
        :payment-info="payment || null"
        :loading="isFetching || isSendingOrders"
        :disabled="isDisabled"
        :clear-selected-orders="isOpened"
        @orders="(orders) => newDistributedOrders = orders"
      />
    </div>
    <template #actions-end>
      <button
        class="btn btn-primary"
        type="button"
        :disabled="isFetching || isDisabled || isSendingOrders"
        @click="onSubmit"
      >
        Сохранить
      </button>
      <button
        class="btn btn-cancel"
        type="button"
        :disabled="isSendingOrders"
        @click="closeModal"
      >
        Отмена
      </button>
    </template>
  </BaseDialog>
</template>
