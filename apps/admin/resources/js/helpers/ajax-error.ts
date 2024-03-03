import { showToast } from 'gts-components/Bootstrap/BootstrapToast/index'

type UseFetchContextError = {
  data: any
  response: Response | null
  error: any
}

export const handleAjaxError = (errorCtx: UseFetchContextError): void => {
  if (errorCtx.response?.status === 401) {
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
