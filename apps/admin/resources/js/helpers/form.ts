import { Ref } from 'vue'

export const validateForm = <T>(form: Ref<HTMLFormElement>, data: Ref<T>): data is Ref<Required<T>> => Boolean(form.value?.reportValidity())

export const isDataValid = (event: any, value: any, customValidate?: boolean): boolean => {
  let isValid = false
  if (Array.isArray(value)) {
    isValid = value.length > 0
  } else {
    isValid = !(value === undefined || value === null || value === '' || (/^\d+$/.test(value) && isNaN(value)) || customValidate === false)
  }
  if (isValid) {
    if (event) {
      event.classList.remove('is-invalid')
    }
    return true
  } if (event) {
    event.classList.add('is-invalid')
  }
  return false
}
