import '../../../sass/partials/components/_card-contacts.scss'

function editableContactRow(route, $tr) {
  const id = $tr.data('id')
  const html = '<td class="column-actions">'
		+ '<div class="actions-menu-wrapper">'
		+ '<div class="icon">more_vert</div>'
		+ '<div class="actions-menu">'
		+ `<button class="btn-edit" data-url="${route}/${id}/edit"><div class="icon">edit</div>Изменить</button>`
		+ '<hr>'
		+ `<button class="btn-delete" data-url="${route}/${id}"><div class="icon">delete</div>Удалить</button>`
		+ '</div>'
		+ '</div>'
		+ '</td>'
  const $actionsTd = $(html).appendTo($tr)

  $actionsTd.find('button.btn-delete').deleteButton()

  $actionsTd.find('button.btn-edit')
    .click(function (e) {
      e.preventDefault()
      WindowDialog({
        url: $(this).data('url'),
        title: 'Изменить контакт',
        buttons: ['submit', 'cancel'],
      })
    })
}

$.fn.cardContacts = function (options = {}) {
  const $el = $(this)
  const route = $el.data('route')
  const isEditable = !!route

  let $addButton = $el.find('button.btn-add')
  if (options.isAddButtonOutsideTable) {
    $addButton = $('button.btn-add')
  }

  $addButton.click(function (e) {
    e.preventDefault()
    WindowDialog({
      url: $(this).data('url') || (`${location.pathname}/contacts/create`),
      title: 'Контакты',
      buttons: ['submit', 'cancel'],
    })
  })

  if (isEditable) {
    $el.find('tr').each((i, tr) => { editableContactRow(route, $(tr)) })
  }
}
