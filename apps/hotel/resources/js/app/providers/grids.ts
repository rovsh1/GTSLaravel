import { datePickerRootAttributeName } from '~lib/date-picker/lib'
import { selectElementDropDownContainer } from '~lib/select-element/select-element'

const popupSelector = '#grid-filters-popup'

export default function bootGrid() {
  const $btn = $('#btn-grid-filters')
  const $popup = $(popupSelector)
  const close = (event: MouseEvent) => {
    if (event.target === null) return
    const target = event.target as HTMLElement
    const parent = target.closest(`${popupSelector}, [${datePickerRootAttributeName}], ${selectElementDropDownContainer}`)
    if (parent !== null) return
    $popup.hide()
    document.removeEventListener('click', close)
  }

  $btn.on('click', (e) => {
    e.preventDefault()
    if (!$popup.is(':hidden')) {
      return
    }

    $popup.fadeIn(200)
    document.addEventListener('click', close)
    e.stopPropagation()
  })

  $popup.find('button[type="reset"]').click((e) => {
    e.preventDefault()
    $popup.find('input').val('')
    $popup.find('select').val([])
    const selects = document.querySelectorAll(`${popupSelector} select`)
    const inputs = document.querySelectorAll(`${popupSelector} input`)
    const combinedElements = Array.from(selects).concat(Array.from(inputs))
    combinedElements.forEach((select) => {
      select.dispatchEvent(new Event('change'))
    })
    $popup.find('.daterange').trigger('customEventClearLitePicker')
  })
}
