import { createPinia } from 'pinia'

import OrderPaymentModal from '~resources/views/payment/main/components/OrderPaymentModal.vue'

import { useApplicationEventBus } from '~lib/event-bus'
import { createVueInstance } from '~lib/vue'

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
