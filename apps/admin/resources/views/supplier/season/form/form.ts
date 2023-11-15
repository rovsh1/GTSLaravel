import { z } from 'zod'

import { useDateRangePicker } from '~lib/date-picker/date-picker'
import { requestInitialData } from '~lib/initial-data'

import '~resources/views/main'

const { seasonID, seasons } = requestInitialData('view-initial-data-supplier-seasons', z.object({
  seasonID: z.number().nullable(),
  seasons: z.array(z.object({
    id: z.number(),
    date_start: z.string(),
    date_end: z.string(),
  })),
}))

$(() => {
  const getBlockedSeasons = () => {
    const blockedSeasons: any = []
    seasons.forEach((season) => {
      if (seasonID !== season.id) {
        blockedSeasons.push([season.date_start, season.date_end])
      }
    })
    return blockedSeasons
  }

  const periodInput = document
    .querySelector<HTMLInputElement>('.daterange')
  if (periodInput !== null) {
    useDateRangePicker(periodInput, {
      disallowLockDaysInRange: true,
      lockDays: getBlockedSeasons(),
    })
  }
})
