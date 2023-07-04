import { Ref } from 'vue'

export const validateForm = <T>(form: Ref<HTMLFormElement>, data: Ref<T>): data is Ref<Required<T>> => Boolean(form.value?.reportValidity())
