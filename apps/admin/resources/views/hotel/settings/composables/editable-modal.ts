import { Ref, ref } from 'vue'

import { MaybeRef, useToggle } from '@vueuse/core'

export interface ModalTypeSettings<T> {
  title: string
  handler: (payload: MaybeRef<T>) => Promise<void>
}

export interface ModalSettings<A, E> {
  add?: ModalTypeSettings<A>
  edit?: ModalTypeSettings<E>
}

export interface UseEditableModalReturn<A, E> {
  isOpened: Readonly<Ref<boolean>>
  isLoading: Readonly<Ref<boolean>>
  title: Readonly<Ref<string>>
  close: () => void
  openAdd: () => void
  openEdit: () => void
  handleAdd: (payload: MaybeRef<A>) => void
  handleEdit: (payload: MaybeRef<E>) => void
}

export const useEditableModal = <A, E>(settings: ModalSettings<A, E>): UseEditableModalReturn<A, E> => {
  const [isOpened, toggleModal] = useToggle()
  const [isLoading, toggleLoading] = useToggle()
  const title = ref<string>('')

  const close = (): void => {
    toggleModal(false)
  }

  const openAdd = (): void => {
    if (!settings.add) {
      return
    }
    title.value = settings.add.title
    toggleModal(true)
  }

  const handleAdd = async (payload: MaybeRef<A>): Promise<void> => {
    if (!settings.add) {
      return
    }
    toggleLoading(true)
    await settings.add.handler(payload)
    toggleLoading(false)
    close()
  }

  const openEdit = (): void => {
    if (!settings.edit) {
      return
    }
    title.value = settings.edit.title
    toggleModal(true)
  }

  const handleEdit = async (payload: MaybeRef<E>): Promise<void> => {
    if (!settings.edit) {
      return
    }
    toggleLoading(true)
    await settings.edit.handler(payload)
    toggleLoading(false)
    close()
  }

  return {
    isOpened,
    isLoading,
    title,
    openEdit,
    openAdd,
    close,
    handleEdit,
    handleAdd,
  }
}
