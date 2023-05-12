import { Litepicker } from 'litepicker'
import { ILPConfiguration } from 'litepicker/dist/types/interfaces'

import { dateRangeDelimiter } from '~lib/date'

import './style.scss'

import {
  datePickerRootAttributeName,
  destroyOldDatePicker,
  prefillDatePickerFromInput,
  registerDatePickerInstance,
  stringifyFormat,
} from './lib'

type Options = Partial<ILPConfiguration>

export const useDatePicker = (element: HTMLInputElement, options?: Options) => {
  destroyOldDatePicker(element)

  const picker = new Litepicker({
    element,
    lang: 'ru-RU',
    showTooltip: false,
    format: stringifyFormat,
    delimiter: dateRangeDelimiter,
    ...options,
  })

  picker.on('render', (ui: HTMLDivElement) => {
    const container = ui.querySelector('.container__main')
    if (container === null) return
    container.setAttribute(datePickerRootAttributeName, '')
  })

  registerDatePickerInstance(element, picker)

  prefillDatePickerFromInput(element, picker)

  return picker
}

export const useDateRangePicker = (element: HTMLInputElement, options?: Options) =>
  useDatePicker(element, {
    singleMode: false,
    numberOfMonths: 2,
    numberOfColumns: 2,
    ...options,
  })
