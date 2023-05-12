import { Litepicker } from 'litepicker'
import { ILPConfiguration } from 'litepicker/dist/types/interfaces'

import { dateRangeDelimiter } from '~lib/date'

import './style.scss'

import { datePickerRootAttributeName, destroyOldDatePicker, registerDatePickerInstance, stringifyFormat } from './lib'

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

  return picker
}
