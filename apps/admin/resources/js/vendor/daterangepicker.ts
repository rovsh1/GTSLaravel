import type daterangepicker from 'daterangepicker'
import { DateTime } from 'luxon'

// eslint-disable-next-line @typescript-eslint/ban-ts-comment
// @ts-expect-error
const { default: moment } = await import('daterangepicker/moment.min')
window.moment = moment
await import('daterangepicker')

const now = DateTime.now()

type Options = daterangepicker.Options

const defaultSettings: Partial<Options> = {
  autoApply: true,
  ranges: {
    'Сегодня': [
      now.toJSDate(),
      now.toJSDate(),
    ],
    // eslint-disable-next-line quote-props
    'Вчера': [
      now.minus({ days: 1 }).toJSDate(),
      now.minus({ days: 1 }).toJSDate(),
    ],
    'Последние 7 дней': [
      now.minus({ days: 6 }).toJSDate(),
      now.toJSDate(),
    ],
    'Последние 30 дней': [
      now.minus({ days: 29 }).toJSDate(),
      now.toJSDate(),
    ],
    'Этот месяц': [
      now.startOf('month').toJSDate(),
      now.endOf('month').toJSDate(),
    ],
    'Прошлый месяц': [
      now.minus({ months: 1 }).startOf('month').toJSDate(),
      now.minus({ months: 1 }).endOf('month').toJSDate(),
    ],
    'Текущий год': [
      now.startOf('year').toJSDate(),
      now.endOf('year').toJSDate(),
    ],
  },
  alwaysShowCalendars: true,
  autoUpdateInput: false,
  opens: 'left',
  drops: 'auto',
  showCustomRangeLabel: false,
  locale: {
    cancelLabel: 'Clear',
    format: 'DD.MM.YYYY',
    daysOfWeek: [
      'Вс',
      'Пн',
      'Вт',
      'Ср',
      'Чт',
      'Пт',
      'Сб',
    ],
    monthNames: [
      'Январь',
      'Февраль',
      'Март',
      'Апрель',
      'Май',
      'Июнь',
      'Июль',
      'Август',
      'Сентябрь',
      'Октябрь',
      'Ноябрь',
      'Декабрь',
    ],
  },
}

export const useDateRangePicker = ($element: JQuery<HTMLElement>, options: Partial<Options>) => {
  let preparedOptions = defaultSettings
  if (options) {
    preparedOptions = {
      ...defaultSettings,
      ...options,
    }
  }

  $element.daterangepicker(preparedOptions)

  $element
    .on('cancel.daterangepicker', (event) => {
      if (event.target === null) return
      $(event.target).val('')
    })
    .on('apply.daterangepicker', (event, picker) => {
      if (event.target === null) return
      $(event.target).val(`${picker.startDate.format('DD.MM.YYYY')} - ${picker.endDate.format('DD.MM.YYYY')}`)
    })
}
