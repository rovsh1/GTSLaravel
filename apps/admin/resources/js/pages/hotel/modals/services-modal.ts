const hidePaidInput = (element: HTMLElement): void => {
  $(`label[for="${element.id}"]`).hide()
  $(element).hide()
  $(element).prop('checked', false)
}

const showPaidInput = (element: HTMLElement): void => {
  $(`label[for="${element.id}"]`).show()
  $(element).show()
}

const initServicesModal = (): void => {
  $('#hotel-services input[type="checkbox"]')
    .each((_: number, element: HTMLElement) => {
      if (element.id.indexOf('_paid_cb') === -1) {
        return
      }
      const serviceInputId: string = element.id.replace('_paid', '')
      const $serviceInput: JQuery<HTMLElement> = $(`#${serviceInputId}`)
      if (!$serviceInput.attr('checked')) {
        hidePaidInput(element)
      }
      $serviceInput.change(function (): void {
        const isChecked = $(this).prop('checked')
        if (isChecked) {
          showPaidInput(element)
        } else {
          hidePaidInput(element)
        }
      })
    })
}

export default initServicesModal
