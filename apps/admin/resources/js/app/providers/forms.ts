import { Tab } from 'bootstrap'

import { useDateRangePicker } from '~lib/date-picker/date-picker'

function bootDeleteButtons() {
  $('button.btn-delete')
    .filter('[data-form-action="delete"]')
    .deleteButton()

  $('a[data-form-action="delete"]')
    .deleteButton()
}

function bootMultiselect() {
  $('select[multiple]').multiselect({
    popupCls: 'dropdown-menu',
  })
}

function bootDateRangePicker() {
  const elements = document.querySelectorAll<HTMLInputElement>('.daterange')
  elements.forEach((element) => {
    useDateRangePicker(element)
  })
}

function bootFileFields() {
  $('div.field-file div.thumb div.btn-remove').click(function (e) {
    e.preventDefault()
    const thumb = $(this).parent()

    window.MessageConfirm('Подверждение удаления', 'Файл будет удален без возможности восстановления, продолжить?', () => {
      thumb.addClass('loading')

      $.ajax({
        url: $(this).data('url'),
        method: 'delete',
        success: () => {
          const wrap = thumb.parent()
          thumb.remove()
          if (wrap.find('>div').length === 0) {
            wrap.remove()
          }
        },
      })
    })
  })
}

function bootTabsAnchor() {
  const anchor = window.location.hash
  if (!anchor) {
    return
  }
  const triggerEl = document.querySelector(`button[data-bs-target="${anchor}"]`)
  if (!triggerEl) {
    return
  }
  Tab.getOrCreateInstance(triggerEl).show()
}

export default function bootForms() {
  bootDeleteButtons()
  bootMultiselect()
  bootDateRangePicker()
  bootFileFields()
  bootTabsAnchor()
}
