import { requestInitialData } from 'gts-common/helpers/initial-data'
import { z } from 'zod'

const { bedTypes } = requestInitialData(
  z.object({
    bedTypes: z.array(z.object({
      id: z.number(),
      name: z.string(),
    })),
  }),
)

interface IBedData {
  id: number
  type_id: number
  beds_number: number
  beds_size: string
}

interface ISelectList {
  id: number
  name: string
}

interface IBedType extends ISelectList {
}

export default function bootBeds($container: JQuery) {
  let I = 0
  const $itemsInner = $('<div class="items"></div>').appendTo($container)

  const getSelect = function (items: IBedType[], name: string, cls: string, emptyItem: string) {
    let html = ''
    html += `<select name="${name}" required class="form-control ${cls}">`
    html += `<option value="">${emptyItem}</option>`
    for (let i = 0, l = items.length; i < l; i++) {
      html += `<option value="${items[i].id}">${items[i].name}</option>`
    }
    html += '</select>'
    return html
  }

  const getTextInput = function (name: string, label: string, field: string) {
    let html = ''
    html += `<label for="form_data_${field}${I}" class="form-element-label  field-text">${label}</label>`
    html += `<input type="text" class="form-control" id="form_data_${field}${I}" name="${name}"/>`
    return html
  }

  const nums: ISelectList[] = []
  for (let i = 1; i <= 12; i++) {
    nums[nums.length] = { id: i, name: i.toString() }
  }
  const bedTypesOptions: IBedType[] = bedTypes

  const addBed = function (data: IBedData | null) {
    const name = `data[beds][${I}]`
    const $item = $('<div class="item"></div>').appendTo($itemsInner)
    const $idInput = $(`<input type="hidden" name="${name}[id]" />`).appendTo($item)
    const $typeSelect = $(getSelect(bedTypesOptions, `${name}[type_id]`, 'bed-type', 'Выберите тип')).appendTo($item)
    $('<span class="m">x</span>').appendTo($item)
    const $numsSelect = $(getSelect(nums, `${name}[beds_number]`, 'bed-nums', 'Выберите кол-во спальных мест')).appendTo($item)
    const $sizeInput = $(getTextInput(`${name}[beds_size]`, 'Размер: ', 'beds_size'))
      .appendTo($item)
      .filter('input')

    $('<div class="btn btn-delete"><i class="icon">delete</i></div>')
      .click(function () {
        $(this).parent().remove()
      })
      .appendTo($item)

    if (data) {
      $idInput.val(data.id)
      $typeSelect.val(data.type_id)
      $numsSelect.val(data.beds_number)
      $sizeInput.val(data.beds_size)
    } else {
      $idInput.val('new')
    }

    I++
  }

  $('<div class="bottom-button"><a href="#" class="btn btn-add"><i class="icon">add</i>Добавить спальное место</a></div>')
    .appendTo($container)
    .find('a')
    .click((e) => {
      e.preventDefault()
      addBed(null)
    })

  const $dataInput = $('#form_data_beds')
  const data = JSON.parse(<string>$dataInput.val() || '[]')
  data.forEach((r: IBedData) => {
    addBed(r)
  })
  $dataInput.remove()
}
