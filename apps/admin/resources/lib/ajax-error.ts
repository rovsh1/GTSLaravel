import { showToast } from '~components/Bootstrap/BootstrapToast'

type UseFetchContextError = {
  data: any
  response: Response | null
  error: any
}

export const handleAjaxError = (errorCtx: UseFetchContextError): void => {
  if (errorCtx.response?.status === 403) {
    location.href = `/login?url=${encodeURIComponent(location.pathname)}`
  } else {
    const message = errorCtx.data?.error || errorCtx.error.message
    showToast({
      title: 'Ошибка',
      description: message,
    }, {
      type: 'danger',
    })
  }
}
