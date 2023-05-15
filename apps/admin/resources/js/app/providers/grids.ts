import { datePickerRootAttributeName } from '~lib/date-picker/lib'

const popupSelector = '#grid-filters-popup'

export default function bootGrid() {
  const $btn = $('#btn-grid-filters')
  const $popup = $(popupSelector)
  const close = (event: MouseEvent) => {
    if (event.target === null) return
    const target = event.target as HTMLElement
    const parent = target.closest(`${popupSelector}, [${datePickerRootAttributeName}]`)
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

    $popup.find('input,select').val('')
  })
}
