import { z } from 'zod'

import { useDateRangePicker } from '~widgets/date-picker/date-picker'

import { requestInitialData } from '~helpers/initial-data'

import '~resources/views/main'

const { seasonID, seasons } = requestInitialData(z.object({
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
      lockDays: getBlockedSeasons(),
    })
  }
})
