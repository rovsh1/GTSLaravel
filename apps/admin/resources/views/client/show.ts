import '~resources/views/main'
import '~resources/js/plugins/card-contacts'

$(document)
  .ready((): void => {
    $('#card-contacts')
      .cardContacts()
  })
