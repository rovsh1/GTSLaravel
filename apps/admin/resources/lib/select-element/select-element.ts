import $ from 'jquery'
import { Options } from 'select2'

import './style.scss'

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
}

const initializeSelectElement = (element: HTMLSelectElement, options?: Options): Promise<JQuery<HTMLElement> | null> => new Promise((resolve) => {
  let multipleOptions: Options = {}
  let select2: SelectElement | null = null
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
          return $selection
        }

        selectionAdapter.prototype.update = function (data: any) {
          this.clear()
          const self = this
          const $rendered = this.$selection.find('.select2-selection__rendered')
          const noItemsSelected = data.length === 0
          || (Array.isArray(this.$element.val()) && this.$element.val().length === 1 && this.$element.val()[0] === '')
          const allItemSelected = data.length === (this.$element.find('option').length || [])
          let formatted = ''

          if (noItemsSelected) {
            formatted = this.options.get('placeholder') || '&nbsp;'
          } else {
            const itemsData = {
              selected: data || [],
              all: this.$element.find('option') || [],
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
          placeholder: options.placeholder || '',
          disabled: options.disabled,
        }
        select2 = new SelectElement(element, {
          ...multipleOptions,
          selectionAdapter,
          templateSelection: (data, elementInstance) => {
            const selectElement = $(elementInstance).parent().parent().parent()
              .prev()
            const selectedElementCount = selectElement.val()
            const selectElementOptionsCount = selectElement.find('option')
              .toArray().map((option) => $(option).attr('value'))
            const isArrayValue = Array.isArray(selectedElementCount)
            if (isArrayValue) {
              const existSelectedEmptyElement = selectedElementCount.some((item) => item === '')
              const existEmptyElement = selectElementOptionsCount.some((item) => item === '')
              return 'Выбрано '
              + `${existSelectedEmptyElement ? selectedElementCount.length - 1 : selectedElementCount.length}`
              + ` из ${existEmptyElement ? selectElementOptionsCount.length - 1 : selectElementOptionsCount.length}`
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
        resolve(select2.select2Instance)
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
          return $selection
        }

        selectionAdapter.prototype.update = function (data: any) {
          console.log('data', data)
          this.clear()
          const self = this
          const $rendered = this.$selection.find('.select2-selection__rendered')
          const noItemsSelected = data.length === 0
          || (Array.isArray(this.$element.val()) && this.$element.val().length === 1 && this.$element.val()[0] === '')
          const allItemSelected = data.length === (this.$element.find('option').length || [])
          let formatted = ''

          if (noItemsSelected) {
            formatted = this.options.get('placeholder') || '&nbsp;'
          } else {
            const itemsData = {
              selected: data || [],
              all: this.$element.find('option') || [],
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

        select2 = new SelectElement(element, {
          selectionAdapter,
          ...options,
        })
        resolve(select2.select2Instance)
      },
    )
  }
})

export const useSelectElement = async (element: HTMLSelectElement | null, options?: Options): Promise<JQuery<HTMLElement> | null> => {
  if (!element) return null
  const select2Instance = await initializeSelectElement(element, options)
  return select2Instance
}
