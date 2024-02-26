export default function bootActionsMenu() {
  const wrapperSelector = '.column-actions .actions-menu-wrapper'
  $(document).on('mouseenter touchstart', wrapperSelector, function () {
    const buttonOffset = $(this).offset()
    const actionMenu = $(this).find('.actions-menu')
    actionMenu.css({
      position: 'fixed',
      top: buttonOffset.top + $(this).outerHeight(),
      left: buttonOffset.left + $(this).outerWidth() - actionMenu.outerWidth(),
    }).show()
  }).on('mouseleave', wrapperSelector, function () {
    $(this).find('.actions-menu').hide()
  })

  $(document).on('touchmove touchstart', (e) => {
    if (!$(e.target).closest(wrapperSelector).length) {
      $('.actions-menu').hide()
    }
  })

  $(window).on('resize', () => {
    $(`${wrapperSelector} .actions-menu`).hide()
  })
}
