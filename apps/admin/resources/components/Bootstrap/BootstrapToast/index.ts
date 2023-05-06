import { createToast, ToastOptions, withProps } from 'mosha-vue-toastify'
import { ContentObject } from 'mosha-vue-toastify/dist/types'

import BootstrapToast from './BootstrapToast.vue'

export const showToast = ({ title, description }: ContentObject, options?: ToastOptions | undefined) => {
  const { close } = createToast(withProps(BootstrapToast, {
    title,
    description,
    onClose: () => close(),
  }), {
    hideProgressBar: true,
    position: 'top-center',
    transition: 'slide',
    showCloseButton: false,
    ...options,
  })
}
