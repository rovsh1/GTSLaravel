import '~resources/views/main'

$(() => {
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
