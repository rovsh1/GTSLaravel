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

const parseMatch = /\d{2}.\d{2}\.\d{4}/

const rangeParseMatch = /\d{2}.\d{2}\.\d{4} - \d{2}.\d{2}\.\d{4}/

export const datePickerRootAttributeName = 'data-date-picker-root'

export const registerDatePickerInstance = (element: HTMLInputElement, picker: Litepicker) => {
  if (window.datePickerInstances === undefined) {
    window.datePickerInstances = []
  }
  window.datePickerInstances.push({ picker, element })
}

export const destroyOldDatePicker = (element: HTMLInputElement) => {
  if (window.datePickerInstances === undefined) {
    return
  }
  const instance = window.datePickerInstances.find((item) => item.element === element)
  if (instance === undefined) {
    return
  }
  instance.picker.destroy()
}

export const prefillDatePickerFromInput = (input: HTMLInputElement, picker: Litepicker) => {
  const value = input.getAttribute('value')
  if (value === null || value === '') {
    return
  }

  if (value.match(rangeParseMatch)) {
    const [start, end] = value.split(dateRangeDelimiter)
    const startDate = DateTime.fromFormat(start, parseFormat).toJSDate()
    const endDate = DateTime.fromFormat(end, parseFormat).toJSDate()
    picker.setDateRange(startDate, endDate)
  } else if (value.match(parseMatch)) {
    const date = DateTime.fromFormat(value, parseFormat).toJSDate()
    picker.setDate(date)
  } else {
    throw new Error(`Value '${value}' from input is not matches datetime format '${parseFormat}'`)
  }
}
