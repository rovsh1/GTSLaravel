import moment, { Moment } from 'moment/moment'

import { useDateRangePicker } from '~resources/js/vendor/daterangepicker'

import { SeasonResponse, useHotelContractGetAPI } from '~api/hotel/contract'

import { useUrlParams } from '~lib/url-params'

import '~resources/views/main'

const { hotel: hotelID, season: seasonID } = useUrlParams()

const handleChangeContract = async ($periodInput: JQuery<HTMLElement>, contractId: number): Promise<void> => {
  const { data: contract, execute: fetchContract } = useHotelContractGetAPI({ hotelID, contractID: contractId })
  await fetchContract()

  useDateRangePicker($periodInput, {
    // @todo ренджи должны быть в будущем
    ranges: undefined,
    isInvalidDate(date: Moment) {
      return contract.value?.seasons?.find((season: SeasonResponse): boolean => {
        const isSameContract = season.contract_id === contractId
        const isSameSeason = season.id === seasonID

        return isSameContract
          && !isSameSeason
          && date.isBetween(season.date_start, season.date_end, 'date', '[]')
      })
    },
    minDate: moment(contract.value?.date_start),
    maxDate: moment(contract.value?.date_end),
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
