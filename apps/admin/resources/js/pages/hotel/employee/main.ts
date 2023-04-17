import '../main'
import '../../../app/components/card-contacts'
import { editableTable } from '../../../app/support/editable-table'

$((): void => {
  editableTable({
    $table: $('.card-grid table'),
    route: window.get_meta_content('hotel-employee-base-route'),
    isEditInModal: false,
  })

  $('#contacts').cardContacts({
    isAddButtonOutsideTable: true,
  })
})
