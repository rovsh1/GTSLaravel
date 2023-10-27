import { camelCase } from 'lodash'

import axios from '~resources/js/app/api'

import { BookingDetailsType } from '~api/booking/service'

import '~resources/views/main'

const getFieldWrapper = (label: string, child: any) => {
  const $label = $(`<label for="form_data_type" class="col-sm-5 col-form-label">${label}</label>`)
  const $inputWrapper = $('<div />', { class: 'col-sm-7 d-flex align-items-center' }).append(child)

  return $('<div />', { class: 'row form-field field-enum field-type field-required' }).append([$label, $inputWrapper])
}

let serviceTypes: BookingDetailsType[] = []
axios.get<BookingDetailsType[]>('/service-booking/details/types')
  .then((response) => {
    serviceTypes = response.data
  })

$(() => {
  const supplierId = $('#form_data_supplier_id').val()
  console.log({ supplierId })
  const $serviceTypeSelect = $<HTMLSelectElement>('#form_data_type')

  $('#form_data_data').remove()

  // eslint-disable-next-line unused-imports/no-unused-vars
  const handleShowTransferToAirport = () => {
    const $input = $('<input />', {
      name: 'data[data][airportId]',
      class: 'form-control',
    })

    $serviceTypeSelect.parent().parent().after(getFieldWrapper('Аэропорт', $input))
  }

  // eslint-disable-next-line unused-imports/no-unused-vars
  const handleShowTransferFromAirport = () => {

  }

  // eslint-disable-next-line unused-imports/no-unused-vars
  const handleShowTransferToRailway = () => {

  }

  // eslint-disable-next-line unused-imports/no-unused-vars
  const handleShowTransferFromRailway = () => {

  }

  // eslint-disable-next-line unused-imports/no-unused-vars
  const handleShowCarRentWithDriver = () => {

  }

  // eslint-disable-next-line unused-imports/no-unused-vars
  const handleShowIntercityTransfer = () => {

  }

  // eslint-disable-next-line unused-imports/no-unused-vars
  const handleShowDayCarTrip = () => {

  }

  // eslint-disable-next-line unused-imports/no-unused-vars
  const handleShowOtherService = () => {

  }

  // eslint-disable-next-line unused-imports/no-unused-vars
  const handleShowCipInAirport = () => {

  }

  const serviceTypeToHandler = (serviceType: BookingDetailsType) => camelCase(`handleShow${serviceType.name}`)

  $serviceTypeSelect.change((e) => {
    const value = Number(e.target.value)

    const selectedServiceType = serviceTypes.find((serviceType) => serviceType.id === value)

    if (!selectedServiceType) {
      throw new Error('unknown service type')
    }

    const handler = serviceTypeToHandler(selectedServiceType)

    // eslint-disable-next-line no-eval
    eval(`${handler}()`)
  })
})
