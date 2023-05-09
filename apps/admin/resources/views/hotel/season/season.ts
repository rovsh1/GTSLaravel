import { DateTime, Interval } from 'luxon'

import { useDateRangePicker } from '~resources/js/vendor/daterangepicker'

import { SeasonResponse, useHotelContractGetAPI } from '~api/hotel/contract'

import { parseAPIDate } from '~lib/date'
import { useUrlParams } from '~lib/url-params'

import '~resources/views/main'

const { hotel: hotelID, season: seasonID } = useUrlParams()

const handleChangeContract = async ($periodInput: JQuery<HTMLElement>, contractId: number): Promise<void> => {
  const { data: contract, execute: fetchContract } = useHotelContractGetAPI({ hotelID, contractID: contractId })
  await fetchContract()

  useDateRangePicker($periodInput, {
    // @todo ренджи должны быть в будущем
    ranges: undefined,
    isInvalidDate: (inputDate: Date) =>
      contract.value?.seasons?.find((season: SeasonResponse): boolean => {
        const isSameContract = season.contract_id === contractId
        const isSameSeason = season.id === seasonID

        const { date_start: dateStart, date_end: dateEnd } = season
        const start = parseAPIDate(dateStart)
        const end = parseAPIDate(dateEnd)
        const inputDateTime = DateTime.fromJSDate(inputDate)

        return isSameContract
          && !isSameSeason
          && Interval.fromDateTimes(start, end).contains(inputDateTime)
      }) !== undefined,
    minDate: contract.value ? parseAPIDate(contract.value.date_start).toJSDate() : undefined,
    maxDate: contract.value ? parseAPIDate(contract.value.date_end).toJSDate() : undefined,
  })
}

$(async () => {
  const $contractSelect = $('#form_data_contract_id')
  const $periodInput = $('.daterange')
  if (!$contractSelect) {
    return
  }

  $periodInput.prop('disabled', true)
  if ($contractSelect.val()) {
    await handleChangeContract($periodInput, Number($contractSelect.val()))
    $periodInput.prop('disabled', false)
  }

  $contractSelect.on('change', async () => {
    $periodInput.prop('disabled', true)
    $periodInput.val('')
    await handleChangeContract($periodInput, Number($contractSelect.val()))
    $periodInput.prop('disabled', false)
  })
})
