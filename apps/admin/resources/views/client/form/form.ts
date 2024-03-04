import { useSelectElement } from 'gts-common/widgets/select-element'

import '~resources/views/main'

$(() => {
  const $legalTypeSelect = $('#form_data_type')
  const $genderInput = $('#form_data_gender')
  const $countryField = $('div.field-country_id')
  const $countrySelect = $countryField.find('#form_data_country_id')
  const $cityField = $('div.field-city_id')
  const $citySelect = $cityField.find('#form_data_city_id')
  let $genderSelect: JQuery<HTMLSelectElement>

  const initGenderSelect = async () => {
    if ($genderSelect !== undefined) {
      return
    }

    // @ts-expect-error тут все ок
    $genderSelect = $('<select />', { class: 'form-select form-control' })
      .append('<option disabled selected></option>')
      .append('<option value="1">Мужской</option>')
      .append('<option value="2">Женский</option>')

    $genderInput.after($genderSelect)
    await useSelectElement($genderSelect[0])
    $genderSelect.change(() => {
      $genderInput.val($genderSelect?.val() || '')
    })
  }

  const toggleFieldsByClientType = async () => {
    const $genderField = $('div.field-gender')
    if ($legalTypeSelect.val() !== '1') {
      $genderField.hide().toggleClass('field-required', false)
      $genderInput.removeAttr('required')
      $genderSelect?.removeAttr('required')

      $citySelect.val('').change()
      $cityField.show().toggleClass('field-required', true)
      $citySelect.attr('required', 'required')
      $countryField.hide().toggleClass('field-required', false)
      $countrySelect.removeAttr('required')
      return
    }
    await initGenderSelect()
    $genderSelect.val('').change()
    $genderField.show().toggleClass('field-required', true)
    $genderSelect.attr('required', 'required')
    $genderInput.attr('required', 'required')

    $countrySelect.val('').change()
    $cityField.hide().toggleClass('field-required', false)
    $citySelect.removeAttr('required')
    $countryField.show().toggleClass('field-required', true)
    $countrySelect.attr('required', 'required')
  }

  $legalTypeSelect
    .ready(toggleFieldsByClientType)
    .change(toggleFieldsByClientType)
})
