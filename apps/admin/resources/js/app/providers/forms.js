import { Tab } from 'bootstrap'

function bootDeleteButtons() {
  $('button.btn-delete')
    .filter('[data-form-action="delete"]')
    .deleteButton()

  $('a[data-form-action="delete"]')
    .deleteButton()
}

function bootMultiselect() {
  $('select[multiple]').multiselect({
    popupCls: 'dropdown-menu',
  })
}

function bootDateRangePicker() {
  $('.daterange').daterangepicker({
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
  })

  $('.daterange')
    .on('cancel.daterangepicker', (event) => {
      $(event.target).val('')
    })
    .on('apply.daterangepicker', (event, picker) => {
      $(event.target).val(`${picker.startDate.format('DD.MM.YYYY')} - ${picker.endDate.format('DD.MM.YYYY')}`)
    })
}

function bootFileFields() {
  $('div.field-file div.thumb div.btn-remove').click(function (e) {
    e.preventDefault()
    const thumb = $(this).parent()

    MessageConfirm('Подверждение удаления', 'Файл будет удален без возможности восстановления, продолжить?', () => {
      thumb.addClass('loading')

      $.ajax({
        url: $(this).data('url'),
        method: 'delete',
        success: () => {
          const wrap = thumb.parent()
          thumb.remove()
          if (wrap.find('>div').length === 0) {
            wrap.remove()
          }
        },
      })
    })
  })
}

function bootTabsAnchor() {
  const anchor = window.location.hash
  if (!anchor) {
    return
  }
  const triggerEl = document.querySelector(`button[data-bs-target="${anchor}"]`)
  if (!triggerEl) {
    return
  }
  Tab.getOrCreateInstance(triggerEl).show()
}

export default function bootForms() {
  bootDeleteButtons()
  bootMultiselect()
  bootDateRangePicker()
  bootFileFields()
  bootTabsAnchor()
}
