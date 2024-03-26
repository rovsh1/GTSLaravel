import { useSelectElement } from 'gts-common/widgets/select-element'

import '~resources/views/main'

$(() => {
  let isFirstInit = true
  const $legalTypeSelect = $('#form_data_type')
  const $genderInput = $('#form_data_gender')
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

    if ($legalTypeSelect.val() === '2') {
      $genderSelect?.val('').change()
    } else if ($legalTypeSelect.val() === '1') {
      await initGenderSelect()
      $genderField.show().toggleClass('field-required', true)
      $genderSelect.attr('required', 'required')
      $genderInput.attr('required', 'required')
      if (!isFirstInit) {
        $genderSelect.val('').change()
      }
    }
    isFirstInit = false
  }

  $legalTypeSelect
    .ready(toggleFieldsByClientType)
    .change(toggleFieldsByClientType)
})
