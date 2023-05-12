import { Litepicker } from 'litepicker'
import { DateTime } from 'luxon'

import { dateRangeDelimiter } from '~lib/date'

type DatePickerInstance = {
  element: HTMLInputElement
  picker: Litepicker
}

declare global {
  interface Window {
    datePickerInstances: DatePickerInstance[]
  }
}

export const stringifyFormat = 'DD.MM.YYYY'
export const parseFormat = 'dd.MM.yyyy'

export const datePickerRootAttributeName = 'data-date-picker-root'

export const registerDatePickerInstance = (element: HTMLInputElement, picker: Litepicker) => {
  if (window.datePickerInstances === undefined) {
    window.datePickerInstances = []
  }
  window.datePickerInstances.push({ picker, element })
}

export const destroyOldDatePicker = (element: HTMLInputElement) => {
  if (window.datePickerInstances === undefined) return
  const instance = window.datePickerInstances
    .find((item) => item.element === element)
  if (instance === undefined) return
  instance.picker.destroy()
}

export const prefillDatePickerFromInput = (picker: Litepicker, input: HTMLInputElement) => {
  const value = input.getAttribute('value')
  if (value === null) return
  const [start, end] = value.split(dateRangeDelimiter)
  const startDate = DateTime.fromFormat(start, parseFormat)
    .toJSDate()
  const endDate = DateTime.fromFormat(end, parseFormat)
    .toJSDate()

  picker.setDateRange(startDate, endDate)
}
