import { Litepicker } from 'litepicker'
import { DateTime, Interval } from 'luxon'
import { z } from 'zod'

import { ContractID, useHotelContractGetAPI } from '~api/hotel/contract'

import { parseAPIDate } from '~lib/date'
import { useDatePicker } from '~lib/date-picker/date-picker'
import { prefillDatePickerFromInput } from '~lib/date-picker/lib'
import { requestInitialData } from '~lib/initial-data'
import { getNullableRef } from '~lib/vue'

import '~resources/views/main'

const { hotelID, seasonID } = requestInitialData(
  'view-initial-data-hotel-season-edit',
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

  const picker = useDatePicker(periodInput, {
    // @todo ренджи должны быть в будущем
    singleMode: false,
    minDate,
    maxDate,
    lockDaysFilter: (inputDate) => {
      if (inputDate === null) return false
      return contract.value?.seasons?.find((season): boolean => {
        const isSameContract = season.contractID === contractID
        const isSameSeason = season.id === seasonID

        const { dateStart, dateEnd } = season
        const start = parseAPIDate(dateStart)
        const end = parseAPIDate(dateEnd)
        const inputDateTime = DateTime.fromJSDate(inputDate.toJSDate())
        const withinInterval = Interval.fromDateTimes(start, end).contains(inputDateTime)

        return isSameContract && !isSameSeason && withinInterval
      }) !== undefined
    },
  })

  prefillDatePickerFromInput(picker, periodInput)

  return picker
}

$(async () => {
  const contractSelect = document
    .querySelector<HTMLInputElement>('#form_data_contract_id')
  const periodInput = document
    .querySelector<HTMLInputElement>('.daterange')
  if (contractSelect === null || periodInput === null) return

  let picker: Litepicker

  periodInput.disabled = true
  if (contractSelect.value !== '') {
    const contractID = z.coerce.number().parse(contractSelect.value)
    picker = await handleChangeContract(periodInput, contractID)
    periodInput.disabled = false
  }

  contractSelect.addEventListener('change', async (event) => {
    const eventTarget = event.target as HTMLSelectElement | null
    if (eventTarget === null) return
    const eventValue = eventTarget.value
    if (eventValue === '') return
    const contractID = z.coerce.number().parse(eventValue)
    picker.destroy()
    periodInput.disabled = true
    picker = await handleChangeContract(periodInput, contractID)
    periodInput.disabled = false
  })
})
