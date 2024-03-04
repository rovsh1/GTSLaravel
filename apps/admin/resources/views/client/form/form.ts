import { requestInitialData } from 'gts-common/helpers/initial-data'
import { useSelectElement } from 'gts-common/widgets/select-element'
import { z } from 'zod'

import '~resources/views/main'

const { id } = requestInitialData(z.object({
  id: z.number().nullable(),
}))

$(() => {
  const isEditMode = id !== null
  let isFirstInit = true
  const $legalTypeSelect = $('#form_data_type')
  const $genderInput = $('#form_data_gender')
  const $countryField = $('div.field-country_id')
  const $countrySelect = $countryField.find('#form_data_country_id')
  const $cityField = $('div.field-city_id')
  const $citySelect = $cityField.find('#form_data_city_id')
  let $genderSelect: JQuery<HTMLSelectElement>
  const editedLegalType = $legalTypeSelect?.val() || null

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
    const preGenderValue = $genderInput.val()
    if (preGenderValue) {
      $genderSelect.val(preGenderValue).change()
    }
    $genderSelect.change(() => {
      $genderInput.val($genderSelect?.val() || '')
    })
  }

  const toggleFieldsByClientType = async () => {
    const $genderField = $('div.field-gender')
    $genderField.hide().toggleClass('field-required', false)
    $genderField.hide().toggleClass('field-required', false)
    $genderInput.removeAttr('required')
    $genderSelect?.removeAttr('required')
    $countryField.hide().toggleClass('field-required', false)
    $countrySelect.removeAttr('required')
    $cityField.hide().toggleClass('field-required', false)
    $citySelect.removeAttr('required')

    if ($legalTypeSelect.val() === '2') {
      $cityField.show().toggleClass('field-required', true)
      $citySelect.attr('required', 'required')
      $genderSelect.val('').change()
      $countrySelect.val('').change()
      if (!isFirstInit) {
        $citySelect.val('').change()
      }
    } else if ($legalTypeSelect.val() === '1' && (!isEditMode || (isEditMode && editedLegalType !== $legalTypeSelect.val()))) {
      await initGenderSelect()
      $genderField.show().toggleClass('field-required', true)
      $genderSelect.attr('required', 'required')
      $genderInput.attr('required', 'required')
      $countryField.show().toggleClass('field-required', true)
      $countrySelect.attr('required', 'required')
      $citySelect.val('').change()
      if (!isFirstInit) {
        $genderSelect.val('').change()
        $countrySelect.val('').change()
      }
    }
    isFirstInit = false
  }

  $legalTypeSelect
    .ready(toggleFieldsByClientType)
    .change(toggleFieldsByClientType)
})
