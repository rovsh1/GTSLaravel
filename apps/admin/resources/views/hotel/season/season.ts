import moment, { Moment } from 'moment/moment'

import { useDateRangePicker } from '~resources/js/vendor/daterangepicker'
import { useHotelContractAPI } from '~resources/lib/api/hotel'
import { Season } from '~resources/lib/models'
import { useUrlParams } from '~resources/lib/url-params'

import '~resources/views/main'

const { hotel: hotelID, season: seasonID } = useUrlParams()

const handleChangeContract = async (contractId: number, $periodInput: JQuery<HTMLElement>): Promise<void> => {
  const { data: contract, execute: fetchContract } = useHotelContractAPI({ hotelID, contractID: contractId })
  await fetchContract()

  useDateRangePicker($periodInput, {
    ranges: undefined,
    isInvalidDate(date: Moment) {
      return contract.value?.seasons?.find((season: Season): boolean => {
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
    await handleChangeContract(Number($contractSelect.val()), $periodInput)
    $periodInput.prop('disabled', false)
  }

  $contractSelect.on('change', async () => {
    $periodInput.prop('disabled', true)
    $periodInput.val('')
    await handleChangeContract(Number($contractSelect.val()), $periodInput)
    $periodInput.prop('disabled', false)
  })
})
