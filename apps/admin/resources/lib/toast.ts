import { createToast } from 'mosha-vue-toastify'
import { ToastContent, ToastOptions } from 'mosha-vue-toastify/dist/types'

import 'mosha-vue-toastify/dist/style.css'

export const showToast = (content: ToastContent, options?: ToastOptions | undefined) =>
  createToast(content, {
    hideProgressBar: true,
    position: 'top-center',
    transition: 'slide',
    ...options,
  })
