import { computed } from 'vue'

import { defineStore } from 'pinia'
import { z } from 'zod'

import { Currency } from '~api/models'

import { requestInitialData } from '~lib/initial-data'

const { order, currencies } = requestInitialData(
  'view-initial-data-hotel-booking',
  z.object({
    order: z.object({
      id: z.number(),
      currency: z.object({
        id: z.number(),
        value: z.string(),
        name: z.string(),
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
    () => currencies.find((cur) => order.currency.value === cur.code_char),
  )

  return {
    currency,
  }
})
