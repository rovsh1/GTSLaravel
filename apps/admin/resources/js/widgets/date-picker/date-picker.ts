import { Litepicker } from 'litepicker'
import { ILPConfiguration } from 'litepicker/dist/types/interfaces'
import { DateTime } from 'luxon'

import { dateRangeDelimiter } from '~helpers/date'

import 'litepicker/dist/plugins/ranges'
import './style.scss'

import {
  datePickerRootAttributeName,
  defaultDisabledDate,
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
    'Прошлый месяц': [
      DateTime.now().minus({ months: 1 }).startOf('month').toJSDate(),
      DateTime.now().minus({ months: 1 }).endOf('month').toJSDate(),
    ],
    'Прошлый год': [
      DateTime.now().minus({ years: 1 }).startOf('year').toJSDate(),
      DateTime.now().minus({ years: 1 }).endOf('year').toJSDate(),
    ],
  }

  let picker: any = null

  if (options?.singleMode) {
    picker = new Litepicker({
      element,
      parentEl: element.parentNode as HTMLElement,
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
      singleMode: false,
      showTooltip: false,
      disallowLockDaysInRange: true,
      format: stringifyFormat,
      delimiter: dateRangeDelimiter,
      plugins: ['ranges'],
      ranges: {
        force: false,
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

  picker.on('ranges.selected', (date1: any, date2: any) => {
    const { lockDays, minDate, maxDate } = picker.options
    if (!lockDays || !Array.isArray(lockDays) || !lockDays.length) return
    const startDate = DateTime.fromJSDate(date1.dateInstance)
    const endDate = DateTime.fromJSDate(date2.dateInstance)
    const selectedPeriodDays = []
    if (startDate.equals(endDate)) {
      selectedPeriodDays.push(startDate)
    } else {
      let currentDate = startDate
      while (currentDate <= endDate) {
        selectedPeriodDays.push(currentDate)
        currentDate = currentDate.plus({ days: 1 })
      }
    }
    let isValidSelectedPeriod = true
    selectedPeriodDays.forEach((dayFromSelectedPeriod) => {
      const isDateInRange = lockDays.some((range) => {
        const startDateTime = DateTime.fromJSDate(range[0].dateInstance)
        const endDateTime = DateTime.fromJSDate(range[1].dateInstance)
        return dayFromSelectedPeriod >= startDateTime && dayFromSelectedPeriod <= endDateTime
      })
      if (minDate) {
        const minDateFormat = DateTime.fromISO(minDate)
        if (dayFromSelectedPeriod < minDateFormat) {
          isValidSelectedPeriod = false
          return
        }
      }
      if (maxDate) {
        const maxDateFormat = DateTime.fromISO(maxDate)
        if (dayFromSelectedPeriod > maxDateFormat) {
          isValidSelectedPeriod = false
          return
        }
      }
      if (isDateInRange) {
        isValidSelectedPeriod = false
      }
    })
    if (!isValidSelectedPeriod || (minDate === defaultDisabledDate && maxDate === defaultDisabledDate)) {
      picker.clearSelection()
    }
  })

  $(element).on('customEventClearLitePicker', () => {
    picker.clearSelection()
  })

  registerDatePickerInstance(element, picker)

  prefillDatePickerFromInput(element, picker)

  return picker
}

export const useDateRangePicker = (element: HTMLInputElement, options?: Options) =>
  useDatePicker(element, {
    numberOfMonths: options?.singleMode ? 1 : 2,
    numberOfColumns: options?.singleMode ? 1 : 2,
    ...options,
  })
