import { useApplicationEventBus } from 'gts-common/helpers/event-bus'
import { createPinia } from 'pinia'

import OrderPaymentModal from '~resources/views/supplier-payment/main/components/BookingPaymentModal.vue'
import { createVueInstance } from '~resources/vue/vue'

import '~resources/views/main'

const pinia = createPinia()
createVueInstance({
  rootComponent: OrderPaymentModal,
  rootContainer: '#order-payment-modal',
  plugins: [pinia],
})

$(() => {
  const eventBus = useApplicationEventBus()

  $('.js-order-pay-link').click(function (e: JQuery.Event) {
    e.preventDefault()
    const paymentId = $(this).data('payment-id')
    eventBus.emit('openOrderPaymentModal', { paymentId: Number(paymentId) })
  })
})
