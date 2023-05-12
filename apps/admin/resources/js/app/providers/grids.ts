import { datePickerRootAttributeName } from '~lib/date-picker/lib'

export default function bootGrid() {
  const $btn = $('#btn-grid-filters')
  const $popup = $('#grid-filters-popup')
  const close = (event: MouseEvent) => {
    if (event.target === null) return
    const target = event.target as HTMLElement
    const parent = target.closest(`#grid-filters-popup, [${datePickerRootAttributeName}]`)
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
