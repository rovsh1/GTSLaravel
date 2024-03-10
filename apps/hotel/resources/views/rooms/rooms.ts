import '~resources/views/main'

$(() => {
  $('.usability-wrapper').each(function () {
    const usabilityCheckerHeight = $(this).find('.usability-height-checker').height()
    const usabilityHeight = $(this).find('.usability').height()
    const expandButton = $(this).find('.usability-expand')
    if (usabilityCheckerHeight !== undefined && usabilityHeight !== undefined && usabilityCheckerHeight <= usabilityHeight) {
      $(this).find('.usability').height(0)
      expandButton.remove()
    }
  })

  $('.usability-expand i').click((element) => {
    $(element.target).parent().parent().toggleClass('expand')
    const existExpandedElement = !!$('.usability-wrapper.expand').length
    if (existExpandedElement) {
      $('.rooms-cards .card').css('height', 'min-content')
    } else {
      $('.rooms-cards .card').css('height', 'auto')
    }
  })
})
