import { Tab } from 'bootstrap'

import { useDateRangePicker } from '~lib/date-picker/date-picker'
import { observeDynamicElements } from '~lib/observe'

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
  const elements = document.querySelectorAll<HTMLInputElement>('.daterange')
  elements.forEach((element) => {
    useDateRangePicker(element)
  })
}

function bootFileFields() {
  $('div.field-file div.thumb div.btn-remove').click(function (e) {
    e.preventDefault()
    const thumb = $(this).parent()

    window.MessageConfirm('Подверждение удаления', 'Файл будет удален без возможности восстановления, продолжить?', () => {
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

function bootGridFilters() {
  const $counterElement = $('#grid-filters-counter')
  const $counterText = $('#grid-filters-counter  span.badge-text')

  const toggleCounter = (isVisible?: boolean) => {
    $counterElement.toggleClass('d-none', !isVisible)
  }

  const setCounterText = (number: number) => {
    $counterText.text(number)
    if (number > 0) {
      toggleCounter(true)
      return
    }
    toggleCounter(false)
  }

  const filledFields: string[] = []

  const $gridFiltersFormInputs = $('#grid-filters-popup input, #grid-filters-popup select')
  $gridFiltersFormInputs.each((_, element) => {
    const $element = $(element)
    const elementName = $element.prop('name')
    if (elementName === '_method') {
      return
    }

    const clearFieldName = elementName.split('[')[0]
    const elementValue = $element.val()?.toString() || ''
    if (!filledFields.includes(clearFieldName) && elementValue.length > 0) {
      filledFields.push(clearFieldName)
    }
  })
  setCounterText(filledFields.length)

  const gridFiltersFormInputsChangeEvent = (event: any) => {
    const $element = $(event.target)
    const clearFieldName = $element.prop('name').split('[')[0]
    const elementValue = $element.val()?.toString() || ''
    const fieldNameIndex = filledFields.indexOf(clearFieldName)
    const isFieldExists = fieldNameIndex !== -1
    if (!isFieldExists && elementValue.length > 0) {
      filledFields.push(clearFieldName)
    }
    if (isFieldExists && elementValue.length === 0) {
      filledFields.splice(fieldNameIndex, 1)
    }
    setCounterText(filledFields.length)
  }

  $gridFiltersFormInputs.on('change customEventChangeLitePicker', (event: any) => {
    gridFiltersFormInputsChangeEvent(event)
  })

  observeDynamicElements(document.getElementById('grid-filters-popup'), 'select', 'change', (event) => { gridFiltersFormInputsChangeEvent(event) })
}

function bootTooltips() {
  // const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
  // const tooltipList = [...tooltipTriggerList].map((tooltipTriggerEl) => new Tooltip(tooltipTriggerEl))
}

export default function bootForms() {
  bootDeleteButtons()
  bootMultiselect()
  bootDateRangePicker()
  bootFileFields()
  bootTabsAnchor()
  bootGridFilters()
  bootTooltips()
}
