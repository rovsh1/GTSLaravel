<script setup lang="ts">

import { computed, ref } from 'vue'

import { useToggle } from '@vueuse/core'
import { useApplicationEventBus } from 'gts-common/helpers/event-bus'

import OrderPaymentsTable from '~resources/views/supplier-payment/main/components/BookingPaymentsTable.vue'

import { bookingsLend, PaymentBookingPayload, useGetPaymentAPI,
  usePaymentBookingsListAPI, usePaymentWaitingBookingsListAPI } from '~api/supplier/payment'

import BaseDialog from '~components/BaseDialog.vue'
import OverlayLoading from '~components/OverlayLoading.vue'

const eventBus = useApplicationEventBus()
const [isOpened, toggleModal] = useToggle()
const paymentID = ref<number>()

const newDistributedOrders = ref<PaymentBookingPayload[]>([])

const paymentIdProps = computed(() => (paymentID.value ? { paymentID: paymentID.value } : null))

const { isFetching: isFetchingWaitingOrders,
  execute: fetchWaitingOrders, data: waitingOrders } = usePaymentWaitingBookingsListAPI(paymentIdProps)
const { isFetching: isFetchingDistributedOrders,
  execute: fetchDistributedOrders, data: distributedOrders } = usePaymentBookingsListAPI(paymentIdProps)
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
  if (!paymentID.value || isFetching.value || isDisabled.value || isSendingOrders.value) return
  isSendingOrders.value = true
  const response = await bookingsLend({
    paymentID: paymentID.value,
    bookings: newDistributedOrders.value,
  })
  isSendingOrders.value = false
  if (response.data.value?.success) {
    closeModal()
    window.location.reload()
  }
}

</script>

<template>
  <BaseDialog
    :auto-width="true"
    :opened="isOpened as boolean"
    :keydown-enter-callback="onSubmit"
    @close="toggleModal(false)"
  >
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
        @keydown.enter.stop.prevent="onSubmit"
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
