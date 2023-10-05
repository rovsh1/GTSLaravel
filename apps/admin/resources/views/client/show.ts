import '~resources/views/main'
import '~resources/js/app/components/card-contacts'

$(document)
  .ready((): void => {
    $('#card-contacts')
      .cardContacts()
  })
