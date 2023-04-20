import '~resources/views/main'
import { WindowDialog } from '~resources/js/libs/dialog/helpers'

$(document).ready(() => {
  const container = $('#profile-settings')
  const settingsUrl = '/profile/'

  container.find('div.block-row').click(function () {
    const action = $(this).data('action')
    if (!action) {
      return
    }

    switch (action) {
      case 'photo':
        WindowDialog({
          url: settingsUrl + action,
          update() {
            const fileInput = this.el.find('input[type="file"]')
            this.el.find('button.btn-primary').click(() => { fileInput.click() })
            fileInput.change(function () {
              // eslint-disable-next-line @typescript-eslint/ban-ts-comment
              // @ts-expect-error
              $(this.form).submit()
            })
            // const img = $('<div class="">' + user_avatar(app.user) + '</div>');
          },
        })
        break
      default:
        WindowDialog({ url: settingsUrl + action })
    }
  })
})
