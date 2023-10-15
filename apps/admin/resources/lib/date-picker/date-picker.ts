import { Litepicker } from 'litepicker'
import { ILPConfiguration } from 'litepicker/dist/types/interfaces'
import { DateTime } from 'luxon'

import { dateRangeDelimiter } from '~lib/date'

import 'litepicker/dist/plugins/ranges'
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

  const ranges = {
    'Сегодня': [
      DateTime.now().toJSDate(),
      DateTime.now().toJSDate(),
    ],
    'Вчера': [
      DateTime.now().minus({ days: 1 }).toJSDate(),
      DateTime.now().minus({ days: 1 }).toJSDate(),
    ],
    'Текущая неделя': [
      DateTime.now().startOf('week').toJSDate(),
      DateTime.now().endOf('week').toJSDate(),
    ],
    'Текущий месяц': [
      DateTime.now().startOf('month').toJSDate(),
      DateTime.now().endOf('month').toJSDate(),
    ],
    'Текущий год': [
      DateTime.now().startOf('year').toJSDate(),
      DateTime.now().endOf('year').toJSDate(),
    ],
    // 'Последние 7 дней': [
    //   DateTime.now().minus({ days: 6 }).toJSDate(),
    //   DateTime.now().toJSDate(),
    // ],
    // 'Последние 14 дней': [
    //   DateTime.now().minus({ days: 13 }).toJSDate(),
    //   DateTime.now().toJSDate(),
    // ],
    // 'Последние 30 дней': [
    //   DateTime.now().minus({ days: 29 }).toJSDate(),
    //   DateTime.now().toJSDate(),
    // ],
    'Прошлый месяц': [
      DateTime.now().minus({ months: 1 }).startOf('month').toJSDate(),
      DateTime.now().minus({ months: 1 }).endOf('month').toJSDate(),
    ],
    // 'Прошлая неделя': [
    //   DateTime.now().minus({ weeks: 1 }).startOf('week').toJSDate(),
    //   DateTime.now().minus({ weeks: 1 }).endOf('week').toJSDate(),
    // ],
    'Прошлый год': [
      DateTime.now().minus({ years: 1 }).startOf('year').toJSDate(),
      DateTime.now().minus({ years: 1 }).endOf('year').toJSDate(),
    ],
  }

  let picker: any = null

  if (options?.singleMode) {
    picker = new Litepicker({
      element,
      lang: 'ru-RU',
      singleMode: true,
      showTooltip: false,
      format: stringifyFormat,
      ...options,
    })
  } else {
    picker = new Litepicker({
      element,
      lang: 'ru-RU',
      singleMode: true,
      showTooltip: false,
      format: stringifyFormat,
      delimiter: dateRangeDelimiter,
      plugins: ['ranges'],
      ranges: {
        customRanges: {
          ...ranges,
        },
      },
      ...options,
    })
  }

  picker.on('render', (ui: HTMLDivElement) => {
    const container = ui.querySelector('.container__main')
    if (container === null) return
    container.setAttribute(datePickerRootAttributeName, '')
  })

  picker.on('selected', () => {
    const customChangeEvent = new CustomEvent('customEventChangeLitePicker')
    element?.dispatchEvent(customChangeEvent)
  })

  $(element).on('customEventClearLitePicker', () => {
    picker.clearSelection()
  })

  registerDatePickerInstance(element, picker)

  prefillDatePickerFromInput(element, picker)

  return picker
}

export const useSingleDatePicker = (element: HTMLInputElement, options?: Options) =>
  useDatePicker(element, {
    numberOfMonths: 1,
    numberOfColumns: 1,
    ...options,
  })

export const useDateRangePicker = (element: HTMLInputElement, options?: Options) =>
  useDatePicker(element, {
    numberOfMonths: 2,
    numberOfColumns: 2,
    ...options,
  })
