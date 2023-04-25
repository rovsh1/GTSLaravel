const errorMessages = {
  403: 'Доступ запрещен',
  404: 'Не найдено'
};

function processResponse(response) {
  if (!response.action) {
    return
  }
  switch (response.action) {
    case 'redirect':
      const url = response.url
      if (url.includes('#')) {
        location.replace(url)
        return location.reload()
      }
      return location.replace(url)
    case 'reload':
      return location.reload()
  }
}

export function ajax(modal, params, callback) {
  params.error = function (xhr) {
    if (xhr.responseJSON && xhr.responseJSON.error) {
      modal.setHtml(xhr.responseJSON.error)
    } else if (errorMessages[xhr.status]) {
      modal.setHtml(errorMessages[xhr.status])
    } else {
      modal.setHtml(xhr.responseText)
    }
    modal.setLoading(false)
    modal.trigger('error')
  }

  params.success = function (r, code, xhr) {
    if (xhr.responseJSON) {
      processResponse(r)
      callback(xhr.responseJSON)
    } else {
      modal.setHtml(r)
      modal.setLoading(false)
      callback(r)
    }
  }

  $.ajax(params)
}

export default function load(modal, params) {
  modal.setLoading(true)

  if (typeof params === 'string') {
    params = { url: params }
  }

  ajax(modal, params, () => {
    modal.trigger('load')
  })
}
