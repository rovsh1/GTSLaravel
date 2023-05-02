export default function bootGrid() {
  const $btn = $('#btn-grid-filters')
  const $popup = $('#grid-filters-popup')
  const close = (e) => {
    const dateRangeChildren = document.querySelectorAll('.daterangepicker *')
    let isClickedByDateRangeChildren = false
    if (dateRangeChildren !== undefined) {
      isClickedByDateRangeChildren = [...dateRangeChildren].find((element) => element === e.target)
    }

    if (!$popup.is(e.target) && $popup.find(e.target).length === 0 && !isClickedByDateRangeChildren) {
      $popup.hide()
      $(document).off('click', close)
    }
  }

  $btn.on('click', (e) => {
    e.preventDefault()
    if (!$popup.is(':hidden')) {
      return
    }

    $popup.fadeIn(200)
    $(document).on('click', close)
    e.stopPropagation()
  })

  $popup.find('button[type="reset"]').click((e) => {
    e.preventDefault()

    $popup.find('input,select').val('')
  })
}
