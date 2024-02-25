import { computed, onMounted } from 'vue'

import { requestInitialData } from 'gts-common/helpers/initial-data'
import { defineStore } from 'pinia'
import { z } from 'zod'

import { Currency } from '~api/models'
import { useGetOrderGuestsAPI } from '~api/order/guest'

const { order, currencies } = requestInitialData(
  z.object({
    order: z.object({
      id: z.number(),
      clientPrice: z.object({
        currency: z.object({
          id: z.number(),
          value: z.string(),
          name: z.string(),
        }),
        value: z.number(),
      }),
      clientId: z.number(),
      legalId: z.number().nullable(),
    }),
    currencies: z.array(z.object({
      id: z.number(),
      code_num: z.number(),
      code_char: z.string(),
      sign: z.string(),
      name: z.string(),
    })),
  }),
)

export const useOrderStore = defineStore('booking-order', () => {
  const currency = computed<Currency | undefined>(
    () => currencies.find((cur) => order.clientPrice.currency.value === cur.code_char),
  )
  const { data: guests, execute: fetchGuests } = useGetOrderGuestsAPI({ orderId: order.id })

  onMounted(() => {
    fetchGuests()
  })

  return {
    currency,
    order,
    guests,
    fetchGuests,
  }
})
