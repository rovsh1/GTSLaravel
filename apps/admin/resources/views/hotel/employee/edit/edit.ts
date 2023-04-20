import '~resources/views/main'
import '~resources/js/app/components/card-contacts'

$((): void => {
  $('#contacts').cardContacts({
    isAddButtonOutsideTable: true,
  })
})
