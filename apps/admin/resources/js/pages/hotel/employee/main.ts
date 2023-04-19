import '../main'
import '../../../app/components/card-contacts'

$((): void => {
  $('#contacts').cardContacts({
    isAddButtonOutsideTable: true,
  })
})
