import $ from 'jquery'
import { Options } from 'select2'

import './style.scss'

type SelectElementOptions = {
  disabled?: boolean
  disabledPlaceholder?: string
  placeholder?: string
  multiple?: boolean
}

class SelectElement {
  private element: JQuery<HTMLSelectElement>

  public select2Instance: JQuery<HTMLElement>

  constructor(initElement: HTMLSelectElement, options: Options) {
    this.element = $(initElement)
    this.select2Instance = this.initializeSelect2(options)
  }

  private initializeSelect2(options: Options): JQuery<HTMLElement> {
    return this.element.select2(options)
  }

  public toogleDisabled(state: boolean): void {
    this.select2Instance.select2('close')
    this.select2Instance.prop('disabled', state)
    this.select2Instance.val([]).trigger('change')
  }
}

const initializeSelectElement = (element: HTMLSelectElement, options?: SelectElementOptions): Promise<SelectElement | null> => new Promise((resolve) => {
  let multipleOptions: Options = {}
  let select2: SelectElement | null = null
  let existEmptyItem = false
  if (options?.multiple) {
    $.fn.select2.amd.require(
      [
        'select2/utils',
        'select2/selection/multiple',
        'select2/selection/placeholder',
        'select2/selection/eventRelay',
        'select2/selection/single',
        'select2/dropdown',
        'select2/dropdown/attachBody',
        'select2/dropdown/search',
      ],
      (Utils, MultipleSelection, Placeholder, EventRelay, SingleSelection, Dropdown, AttachBody, Search) => {
        let selectionAdapter = Utils.Decorate(MultipleSelection, Placeholder)
        selectionAdapter = Utils.Decorate(selectionAdapter, EventRelay)
        selectionAdapter.prototype.render = function () {
          const $selection = SingleSelection.prototype.render.call(this)
          const emptyItem = this.$element.find('option').toArray().find((option: HTMLElement) =>
            $(option).attr('value') === '' && $(option).text().trim() === '')
          existEmptyItem = !!emptyItem
          if (existEmptyItem) {
            $(emptyItem).prop('disabled', true).hide()
          }
          return $selection
        }
        selectionAdapter.prototype.update = function (data: any) {
          this.clear()
          const $rendered = this.$selection.find('.select2-selection__rendered')
          const noItemsSelected = data.length === 0
          || (Array.isArray(this.$element.val()) && data.length === 1 && data[0].id === '')
          const allItemSelected = data.length === (this.$element.find('option').length || [])
          let formatted = ''
          if (noItemsSelected) {
            formatted = (options?.disabled || this.$element.prop('disabled')) ? options?.disabledPlaceholder || '&nbsp;' : options?.placeholder || '&nbsp;'
          } else {
            const selectedElementsCount = data ? data.length : 0
            const allElementsCount = this.$element.find('option').length
            const existEmptyItemOnSelected = data?.some((item: any) => item.id === '')
            const itemsData = {
              selected: existEmptyItem && existEmptyItemOnSelected ? selectedElementsCount - 1 : selectedElementsCount,
              all: existEmptyItem ? allElementsCount - 1 : allElementsCount,
            }
            formatted = this.display(itemsData, $rendered)
          }
          const $selectAll = $(
            `<a class="action-all" href="#">
            ${allItemSelected ? 'Снять всё' : 'Выбрать всё'}</a>`,
          )
          if ($(this.$selection).attr('aria-expanded') === 'true') {
            $selectAll.show()
          } else {
            $selectAll.hide()
          }
          const self = this
          $rendered.empty().append(formatted)
          $rendered.append($selectAll)
          $selectAll.on('click', (e) => {
            e.preventDefault()
            e.stopPropagation()
            if (allItemSelected) {
              self.$element.val([]).trigger('change')
              self.$element.select2('close')
              self.$element.select2('open')
            } else {
              const allItems = self.$element.find('option').toArray().map((option: any) => $(option).attr('value'))
              self.$element.val(allItems).trigger('change.select2')
              self.$element.select2('close')
              self.$element.select2('open')
            }
          })
          $rendered.prop('title', formatted === '&nbsp;' ? '' : formatted)
        }

        const dropdownWithSearch = Utils.Decorate(Dropdown, Search)

        dropdownWithSearch.prototype.render = function () {
          const $rendered = Dropdown.prototype.render.call(this)
          const $search = $(
            '<span class="select2-search select2-search--dropdown">'
          + '<input class="select2-search__field" type="search"'
          + ' tabindex="-1" autocomplete="off" autocorrect="off" autocapitalize="off"'
          + ' spellcheck="false" role="textbox" />'
          + '</span>',
          )

          this.$searchContainer = $search
          this.$search = $search.find('input')

          $rendered.prepend($search)
          return $rendered
        }

        const dropdownAdapter = Utils.Decorate(dropdownWithSearch, AttachBody)

        multipleOptions = {
          multiple: true,
          closeOnSelect: false,
          disabled: options?.disabled,
        }
        select2 = new SelectElement(element, {
          ...multipleOptions,
          selectionAdapter,
          templateSelection: (data: any) => {
            if (data && data.selected && data.all) {
              return 'Выбрано '
              + `${data.selected}`
              + ` из ${data.all}`
            }
            return ''
          },
          dropdownAdapter,
        })
        select2.select2Instance.on('select2:opening', (e) => {
          $(e.target).next().find('.action-all').show()
        })
        select2.select2Instance.on('select2:closing', (e) => {
          $(e.target).next().find('.action-all').hide()
        })
        resolve(select2)
      },
    )
  } else {
    $.fn.select2.amd.require(
      [
        'select2/utils',
        'select2/selection/placeholder',
        'select2/selection/eventRelay',
        'select2/selection/single',
      ],
      (Utils, Placeholder, EventRelay, SingleSelection) => {
        let selectionAdapter = Utils.Decorate(SingleSelection, Placeholder)
        selectionAdapter = Utils.Decorate(selectionAdapter, EventRelay)
        selectionAdapter.prototype.render = function () {
          const $selection = SingleSelection.prototype.render.call(this)
          const emptyItem = this.$element.find('option').toArray().find((option: HTMLElement) =>
            $(option).attr('value') === '' && $(option).text().trim() === '')
          existEmptyItem = !!emptyItem
          if (existEmptyItem) {
            $(emptyItem).prop('disabled', true).hide()
          }
          return $selection
        }

        selectionAdapter.prototype.update = function (data: any) {
          this.clear()
          const self = this
          const $rendered = this.$selection.find('.select2-selection__rendered')
          const noItemsSelected = data.length === 0 || (existEmptyItem && data.length === 1 && data[0].id === '')
          let formatted = ''

          if (noItemsSelected) {
            formatted = (options?.disabled || this.$element.prop('disabled')) ? options?.disabledPlaceholder || '&nbsp;' : options?.placeholder || '&nbsp;'
          } else {
            const itemData = {
              selected: data[0] || null,
            }
            formatted = this.display(itemData, $rendered)
          }
          const $clearSelectElemetny = $(
            '<a class="clear-select-element" href="#">Очитстить</a>',
          )
          if (!noItemsSelected) {
            $clearSelectElemetny.show()
          } else {
            $clearSelectElemetny.hide()
          }
          if (existEmptyItem) {
            $rendered.parent().parent().find('.clear-select-element').remove()
            $rendered.parent().parent().append($clearSelectElemetny)
            $clearSelectElemetny.on('click', (e) => {
              e.preventDefault()
              e.stopPropagation()
              $(e.target).hide()
              self.$element.val([]).trigger('change')
              self.$element.select2('close')
            })
          }
          $rendered.empty().append(formatted)
          $rendered.prop('title', formatted === '&nbsp;' ? '' : formatted)
        }

        select2 = new SelectElement(element, {
          selectionAdapter,
          templateSelection: (data: any) => (data && data.selected ? data.selected.text : options?.placeholder),
          disabled: options?.disabled,
        })
        resolve(select2)
      },
    )
  }
})

export const useSelectElement = async (element: HTMLSelectElement | null, options?: SelectElementOptions): Promise<SelectElement | null> => {
  if (!element) return null
  const select2Element = await initializeSelectElement(element, options)
  return select2Element
}
