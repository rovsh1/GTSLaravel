export default function bootSitemap() {
  let menuOpenFlag = false
  let isFirstLoadPage = true
  let hoveredEffectActive = false

  function initMenuMode() {
    const menuOpenFlagFromStorage = $.cookie('menu_open_flag')
    if (menuOpenFlagFromStorage) {
      menuOpenFlag = !!JSON.parse(menuOpenFlagFromStorage)
    } else {
      menuOpenFlag = false
    }

    const checkDisableAnimation = (isFirstLoadPage && menuOpenFlag) || (menuOpenFlag && hoveredEffectActive)
    if (checkDisableAnimation) {
      $('body').addClass('sitemap-disable-animation')
    } else {
      $('body').removeClass('sitemap-disable-animation')
    }
    if (menuOpenFlag) {
      $('.btn-sitemap-toggle-switch').addClass('rotate-btn')
      $('body').removeClass('sitemap-absolute-mode')
      $('body').addClass('sitemap-expanded')
    } else {
      $('.btn-sitemap-toggle-switch').removeClass('rotate-btn')
      $('body').addClass('sitemap-absolute-mode')
      $('body').removeClass('sitemap-expanded')
    }
    isFirstLoadPage = false
  }

  $('#sitemap-categories, #btn-sitemap').mouseenter((e) => {
    e.stopPropagation()
    if (menuOpenFlag) return
    if (!document.body.classList.contains('sitemap-expanded')) {
      document.body.classList.add('sitemap-expanded')
    }
    hoveredEffectActive = true
  })

  $('.sitemap-wrapper').mouseleave((e) => {
    e.stopPropagation()
    if (menuOpenFlag) return
    if (document.body.classList.contains('sitemap-expanded')) {
      document.body.classList.remove('sitemap-expanded')
    }
    hoveredEffectActive = false
  })

  $('#sitemap-categories a').click(function (e) {
    e.preventDefault()

    const item = $(this).parent()
    const category = item.data('category')

    $('#sitemap-categories .current').removeClass('current')
    item.addClass('current')

    const menus = $('#sitemap-categories-menus > aside')
    menus.each((i, m) => {
      if ($(m).data('category') === category) {
        $(m).show()
      } else {
        $(m).hide()
      }
    })
  })

  if ($('#sitemap-categories .current').length === 0) {
    $('#sitemap-categories div:first-child a').click()
  }

  $('#btn-sidebar-toggle').click(() => {
    $('.current-category-sidebar').toggleClass('submenu-collapsed')
    $(this).find('i')
  })

  $('.btn-sitemap-toggle-switch').click((e) => {
    if (menuOpenFlag) {
      $.cookie('menu_open_flag', 'false', { sameSite: 'None', secure: false })
    } else {
      $.cookie('menu_open_flag', 'true', { sameSite: 'None', secure: false })
    }
    initMenuMode()
    hoveredEffectActive = false
  })

  initMenuMode()
}
