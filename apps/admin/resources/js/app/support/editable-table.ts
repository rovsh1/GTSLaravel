const editableRow = ($tr: JQuery<HTMLTableRowElement>, route: string, isEditInModal: boolean): void => {
  const id = $tr.data('id')
  if (!id) {
    return
  }

  const html: string = '<td class="column-actions">'
        + '<div class="actions-menu-wrapper">'
        + '<div class="icon">more_vert</div>'
        + '<div class="actions-menu">'
        + `<button class="btn-edit" data-url="${route}/${id}/edit"><div class="icon">edit</div>Изменить</button>`
        + '<hr>'
        + `<button class="btn-delete" data-url="${route}/${id}"><div class="icon">delete</div>Удалить</button>`
        + '</div>'
        + '</div>'
        + '</td>'

  const $actionsTd = $(html)
    .appendTo($tr)

  $actionsTd.find('button.btn-delete')
    .deleteButton()

  $actionsTd.find('button.btn-edit')
    .click(function (): void {
      if (!isEditInModal) {
        window.location.replace($(this).data('url'))
        return
      }
      window.WindowDialog({
        url: $(this)
          .data('url'),
        title: 'Изменить контакт',
        buttons: ['submit', 'cancel'],
      })
    })
}

export interface EditableTableProps {
    $table: JQuery<HTMLElement>
    route?: string
    isEditInModal?: boolean
}

export const editableTable = (props: EditableTableProps): void => {
  if (props.isEditInModal === undefined) {
    // eslint-disable-next-line no-param-reassign
    props.isEditInModal = true
  }
  let preparedRoute = props.route
  if (!preparedRoute) {
    preparedRoute = props.$table.data('route')
  }

  const isEditable = !!preparedRoute
  if (isEditable) {
    props.$table.find('tr')
      .each((_: number, tr: HTMLTableRowElement) => editableRow($(tr), preparedRoute as string, props.isEditInModal as boolean))

    props.$table.find('thead tr').append('<th></th>')
  }
}
