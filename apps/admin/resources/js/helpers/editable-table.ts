const editableRow = ($tr: JQuery<HTMLTableRowElement>, route: string, isEditInModal: boolean, canEdit: boolean, canDelete: boolean): void => {
  const id = $tr.data('id')
  if (!id) {
    return
  }

  let html: string = '<td class="column-actions">'
        + '<div class="actions-menu-wrapper">'
        + '<div class="icon">more_vert</div>'
        + '<div class="actions-menu">'
  if (canEdit) {
    html += `<button class="btn-edit" data-url="${route}/${id}/edit"><div class="icon">edit</div>Изменить</button>`
  }
  if (canDelete) {
    html += '<hr>'
        + `<button class="btn-delete" data-url="${route}/${id}"><div class="icon">delete</div>Удалить</button>`
  }
  html += '</div>'
        + '</div>'
        + '</td>'

  const $actionsTd = $(html).appendTo($tr)

  $actionsTd.find('button.btn-delete').deleteButton()

  $actionsTd.find('button.btn-edit')
    .click(function (): void {
      if (!isEditInModal) {
        window.location.replace($(this).data('url'))
        return
      }
      window.WindowDialog({
        url: $(this).data('url'),
        title: 'Изменить запись',
        buttons: ['submit', 'cancel'],
      })
    })
}

export interface EditableTableProps {
  $table: JQuery<HTMLElement>
  route?: string
  isEditInModal?: boolean
  canEdit?: boolean
  canDelete?: boolean
}

export const editableTable = (props: EditableTableProps): void => {
  let isEditInModal = true
  if (props.isEditInModal !== undefined) {
    isEditInModal = props.isEditInModal
  }
  let canEdit = true
  if (props.canEdit !== undefined) {
    canEdit = props.canEdit
  }
  let canDelete = true
  if (props.canDelete !== undefined) {
    canDelete = props.canDelete
  }
  let preparedRoute = props.route
  if (!preparedRoute) {
    preparedRoute = props.$table.data('route')
  }

  const isEditable = !!preparedRoute && (canEdit || canDelete)
  if (isEditable) {
    props.$table.find('tr')
      .each((_: number, tr: HTMLTableRowElement) => editableRow(
        $(tr),
        preparedRoute as string,
        isEditInModal,
        canEdit,
        canDelete,
      ))

    props.$table.find('thead tr').append('<th></th>')
  }
}
