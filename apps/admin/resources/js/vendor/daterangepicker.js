import '~resources/js/vendor/moment'
import 'daterangepicker'

const defaultSettings = {
  autoApply: true,
  ranges: {
    // eslint-disable-next-line quote-props
    'Сегодня': [moment(), moment()],
    // eslint-disable-next-line quote-props
    'Вчера': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
    'Последние 7 дней': [moment().subtract(6, 'days'), moment()],
    'Последние 30 дней': [moment().subtract(29, 'days'), moment()],
    'Этот месяц': [moment().startOf('month'), moment().endOf('month')],
    'Прошлый месяц': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
    'Текущий год': [moment().startOf('year'), moment().endOf('year')],
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

export const useDateRangePicker = ($element, options) => {
  let preparedOptions = defaultSettings
  if (options) {
    preparedOptions = {
      ...defaultSettings,
      ...options
    }
  }

  $element.daterangepicker(preparedOptions)

  $element
    .on('cancel.daterangepicker', (event) => {
      $(event.target).val('')
    })
    .on('apply.daterangepicker', (event, picker) => {
      $(event.target).val(`${picker.startDate.format('DD.MM.YYYY')} - ${picker.endDate.format('DD.MM.YYYY')}`)
    })
}

