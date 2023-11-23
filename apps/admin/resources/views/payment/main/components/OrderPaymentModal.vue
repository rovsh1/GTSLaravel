<script setup lang="ts">

import { ref } from 'vue'

import { useToggle } from '@vueuse/core'

import OrderPaymentsTable from '~resources/views/payment/main/components/OrderPaymentsTable.vue'

import { useApplicationEventBus } from '~lib/event-bus'

import BaseDialog from '~components/BaseDialog.vue'

const eventBus = useApplicationEventBus()
const [isOpened, toggleModal] = useToggle()
const paymentId = ref<number>()

eventBus.on('openOrderPaymentModal', (event: { paymentId: number }) => {
  paymentId.value = event.paymentId
  toggleModal(true)
})

const closeModal = () => {
  paymentId.value = undefined
  toggleModal(false)
}

const onSubmit = () => {
  console.log('onSubmit', paymentId.value)
  closeModal()
  window.location.reload()
}

</script>

<template>
  <BaseDialog :auto-width="true" :opened="isOpened as boolean" @close="toggleModal(false)">
    <template #title>Распределение оплат</template>

    <OrderPaymentsTable
      :payment-id="paymentId"
      :orders="[]"
      :loading="false"
    />

    <template #actions-end>
      <button class="btn btn-primary" type="button" @click="onSubmit">
        Сохранить
      </button>
      <button class="btn btn-cancel" type="button" @click="closeModal">Отмена</button>
    </template>
  </BaseDialog>
</template>
