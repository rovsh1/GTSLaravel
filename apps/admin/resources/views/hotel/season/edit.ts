import { Litepicker } from 'litepicker'
import { z } from 'zod'

import { getNullableRef } from '~resources/vue/vue'

import { ContractID, useHotelContractGetAPI } from '~api/hotel/contract'

import { useDateRangePicker } from '~widgets/date-picker/date-picker'

import { formatUtcToIsoDate } from '~helpers/date'
import { requestInitialData } from '~helpers/initial-data'

import '~resources/views/main'

const { hotelID, seasonID } = requestInitialData(
  z.object({
    hotelID: z.number(),
    seasonID: z.optional(z.number()),
  }),
)

const handleChangeContract = async (periodInput: HTMLInputElement, contractID: ContractID) => {
  const { data: contract, execute } = useHotelContractGetAPI({ hotelID, contractID })
  await execute()

  const getContract = getNullableRef(contract, (data) => data, undefined)
  const minDate = getContract?.dateStart
  const maxDate = getContract?.dateEnd

  const blockedSeasons = contract.value?.seasons?.map((season) => {
    const isSameContract = season.contractID === contractID
    const isSameSeason = season.id === seasonID

    const { dateStart, dateEnd } = season
    if (isSameContract && !isSameSeason) {
      return [dateStart, dateEnd]
    }
    return undefined
  }).filter(Boolean) || []
  return useDateRangePicker(periodInput, {
    lockDays: blockedSeasons,
    minDate: minDate ? formatUtcToIsoDate(minDate) : undefined,
    maxDate: maxDate ? formatUtcToIsoDate(maxDate) : undefined,
  })
}

$(async () => {
  const contractSelect = document
    .querySelector<HTMLInputElement>('#form_data_contract_id')
  let periodInput = document
    .querySelector<HTMLInputElement>('.daterange')
  if (contractSelect === null || periodInput === null) {
    return
  }

  let picker: Litepicker

  periodInput.disabled = true
  if (contractSelect.value !== '') {
    const contractID = z.coerce.number().parse(contractSelect.value)
    picker = await handleChangeContract(periodInput, contractID)
    periodInput = document.querySelector<HTMLInputElement>('.daterange') as HTMLInputElement
    periodInput.disabled = false
  }

  $(contractSelect).on('change', async (event: any) => {
    periodInput = document.querySelector<HTMLInputElement>('.daterange') as HTMLInputElement
    if (!seasonID) {
      periodInput.value = ''
    }
    const eventTarget = $(event.target)
    if (eventTarget === null) {
      return
    }
    const eventValue = eventTarget.val()
    if (eventValue === '') {
      return
    }
    const contractID = z.coerce.number().parse(eventValue)
    if (picker) {
      picker.destroy()
    }
    periodInput.disabled = true
    picker = await handleChangeContract(periodInput, contractID)
    periodInput = document.querySelector<HTMLInputElement>('.daterange') as HTMLInputElement
    periodInput.disabled = false
  })
})
