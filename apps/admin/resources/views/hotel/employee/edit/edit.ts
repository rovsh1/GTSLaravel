import '~resources/views/main'
import '~resources/js/plugins/card-contacts'

$((): void => {
  $('#contacts').cardContacts({
    isAddButtonOutsideTable: true,
  })
})
