import { z } from 'zod'

import { requestInitialData } from '~lib/initial-data'

import '~resources/views/main'

const { seasons } = requestInitialData('view-initial-data-supplier-seasons', z.object({
  seasons: z.array(z.object({
    id: z.number(),
    date_start: z.string(),
    date_end: z.string(),
  })),
}))

$(() => {
  console.log(seasons)
})
