import { z } from 'zod'

import axios from '~resources/js/app/api'

import { showConfirmDialog } from '~lib/confirm-dialog'
import { requestInitialData } from '~lib/initial-data'

import '~resources/views/main'

const { createUserUrl, searchUserUrl } = requestInitialData('view-initial-data-client-user', z.object({
  createUserUrl: z.string(),
  searchUserUrl: z.string(),
}))

$(() => {
  const $addButton = $('a.btn-add')

  const createNewUserHandler = () => {
    window.location.href = createUserUrl
  }

  $addButton.click((e) => {
    e.preventDefault()

    const $form = $(`<form method="POST" action="${window.location.href}"><input type="hidden" name="_method" value="post"/><div class="row form-field field-select field-user_id field-required">
    <label for="form_data_user_id" class="col-sm-5 col-form-label">Пользователь</label><div class="col-sm-7 d-flex align-items-center"><select class="form-select form-control" name="user_id" id="form_data_user_id"></select></div></div></form>`)

    window.WindowDialog({
      html: $form,
      title: 'Добавить пользователя',
      buttons: ['submit', 'cancel', { text: 'Новый пользователь', cls: 'btn', handler: createNewUserHandler }],
      update: () => {
        $('#form_data_user_id').select2({
          dropdownParent: $('.modal-dialog .modal-body'),
          ajax: {
            url: searchUserUrl,
            dataType: 'json',
            delay: 250,
            data: (params) => ({
              name: params.term,
              type: 'public',
            }),
            processResults: (data) => {
              const results = data.map(({ id, name }: { id: string; name: string }) => ({ id, text: name }))
              return { results }
            },
          },
        })
      },
    })
  })

  const selectedUsers: string[] = []

  const $deleteBookingsButton = $('<a />', {
    href: '#',
    html: '<i class="icon">delete</i>Удалить пользователей',
    class: 'btn btn-delete text-danger border-0 disabled',
  }).click(async (event) => {
    event.preventDefault()

    const { result: isConfirmed, toggleLoading } = await showConfirmDialog('Удалить запись?', 'btn-danger')
    if (isConfirmed) {
      toggleLoading()
      const url = `${window.location.pathname.replace(/\/$/, '')}/bulk`
      await axios.delete(url, { data: { ids: selectedUsers } })
      location.reload()
    }
  })

  $('.content-header a.btn-add').after($deleteBookingsButton)

  $('.js-select-user').change((event: any): void => {
    const $checkbox = $(event.target)
    const bookingId = $checkbox.data('user-id')
    if ($checkbox.is(':checked')) {
      selectedUsers.push(bookingId)
      $deleteBookingsButton.toggleClass('disabled', false)
      return
    }

    const index = selectedUsers.indexOf(bookingId)
    if (index !== -1) {
      selectedUsers.splice(index, 1)
      if (selectedUsers.length === 0) {
        $deleteBookingsButton.toggleClass('disabled', true)
      }
    }
  })
})
