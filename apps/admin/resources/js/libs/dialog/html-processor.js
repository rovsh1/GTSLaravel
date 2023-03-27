import { defaultHandlers } from './buttons-builder'
import { ajax } from './loader'

function processForm(modal, form) {
  form.submit(function (e) {
    e.preventDefault()
    if (modal.trigger('beforeSubmit', form) === false) {
      return
    }

    modal.setLoading(true)
    const data = new FormData(this)
    const url = modal.get('url')

    ajax(modal, {
      url: form.attr('action') || (is_string(url) ? url : url.url),
      method: 'post',
      data,
      cache: false, // dataType: 'json',
      contentType: false,
      processData: false,
    }, (r) => {
      modal.trigger('submit', r)
    })
  })

  if (modal.get('autofocus')) {
    form.find('input[type!="hidden"],textarea').eq(0).focus()
  }
}

export default function processHtml(modal, body) {
  body.find('a').click(function (e) {
    const url = $(this).data('url')
    if (url) {
      e.preventDefault()
      modal.load(url)
    }
  })

  body.find('button,a').click(function (e) {
    const action = $(this).data('action')
    if (action && defaultHandlers[action]) {
      e.preventDefault()
      defaultHandlers[action].call(modal)
    }
  })

  const firstEl = $(body[0].firstChild)
  if (firstEl.data('title')) {
    modal.setTitle(firstEl.data('title'))
  }

  if (firstEl.data('cls')) {
    modal.set('cls', firstEl.data('cls'))
  }

  const form = body.find('form')
  if (form.length && modal.get('processForm')) {
    processForm(modal, form)
  }
}
