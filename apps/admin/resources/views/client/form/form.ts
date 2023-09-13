$(() => {
  const $legalTypeSelect = $('#form_data_type')
  const $genderInput = $('#form_data_gender')
  let $genderSelect: JQuery<HTMLSelectElement>

  const initGenderSelect = (): void => {
    if ($genderSelect !== undefined) {
      return
    }

    // @ts-expect-error тут все ок
    $genderSelect = $('<select />', { class: 'form-select form-control' }).append('<option disabled selected></option>').append('<option value="1">Мужской</option>').append('<option value="2">Женский</option>')

    $genderInput.after($genderSelect)
    $genderSelect.change(() => {
      $genderInput.val($genderSelect?.val() || '')
    })
  }

  const toggleGenderField = (): void => {
    const $genderField = $('div.field-gender')
    if ($legalTypeSelect.val() !== '1') {
      $genderField.hide().toggleClass('field-required', false)
      $genderInput.removeAttr('required')
      return
    }
    initGenderSelect()
    $genderSelect.val('')
    $genderField.show().toggleClass('field-required', true)
    $genderInput.attr('required', 'required')
  }

  $legalTypeSelect
    .ready(toggleGenderField)
    .change(toggleGenderField)
})
