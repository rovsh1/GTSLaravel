import { z } from 'zod'

import axios from '~resources/js/app/api'

import { showConfirmDialog } from '~lib/confirm-dialog'
import { requestInitialData } from '~lib/initial-data'

import '~resources/views/main'

const { createUserUrl } = requestInitialData('view-initial-data-client-user', z.object({
  createUserUrl: z.string(),
}))

$(() => {
  const $addButton = $('a.btn-add')

  const createNewUserHandler = () => {
    window.location.href = createUserUrl
  }

  $addButton.click(function (e) {
    e.preventDefault()
    window.WindowDialog({
      url: $(this).attr('href'),
      title: 'Добавить пользователя',
      buttons: ['submit', 'cancel', { text: 'Новый пользователь', cls: 'btn', handler: createNewUserHandler }],
      update: () => {
        $('#form_data_user_id').select2({ dropdownParent: $('.modal-dialog .modal-body') })
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
