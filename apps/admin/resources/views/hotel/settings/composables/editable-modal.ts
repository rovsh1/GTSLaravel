import { readonly, Ref, ref } from 'vue'

import { MaybeRef, useToggle } from '@vueuse/core'

export interface ModalTypeSettings<T> {
  title: string
  handler: (payload: MaybeRef<T>) => Promise<void>
}

export interface ModalSettings<A, E> {
  add?: ModalTypeSettings<A>
  edit?: ModalTypeSettings<E>
}

export interface UseEditableModalReturn<A, E, T> {
  isOpened: Readonly<Ref<boolean>>
  isLoading: Readonly<Ref<boolean>>
  title: Readonly<Ref<string>>
  editableId: Readonly<Ref<number | undefined>>
  editableObject: Ref<T | undefined>
  close: () => void
  openAdd: () => void
  openEdit: (id: number, object: T) => void
  submit: (payload: MaybeRef<A | E>) => void
}

export const useEditableModal = <A, E, T>(settings: ModalSettings<A, E>): UseEditableModalReturn<A, E, T> => {
  const [isOpened, toggleModal] = useToggle()
  const [isLoading, toggleLoading] = useToggle()
  const title = ref<string>('')
  const editableId = ref<number>()
  const editableObject = ref<T>()

  const close = (): void => {
    editableId.value = undefined
    editableObject.value = undefined
    toggleModal(false)
  }

  const openAdd = (): void => {
    if (!settings.add) {
      return
    }
    title.value = settings.add.title
    toggleModal(true)
  }

  const openEdit = (id: number, object: T): void => {
    if (!settings.edit) {
      return
    }
    editableId.value = id
    editableObject.value = object
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

  const handleAdd = async (payload: MaybeRef<A>): Promise<void> => {
    if (!settings.add) {
      return
    }
    toggleLoading(true)
    await settings.add.handler(payload)
    toggleLoading(false)
    close()
  }

  const submit = async (payload: MaybeRef<A | E>): Promise<void> => {
    if (editableId.value === undefined) {
      await handleAdd(payload as A)
    } else {
      await handleEdit(payload as E)
    }
  }

  return {
    editableId: readonly(editableId),
    editableObject,
    isOpened,
    isLoading,
    title: readonly(title),
    openEdit,
    openAdd,
    close,
    submit,
  }
}
