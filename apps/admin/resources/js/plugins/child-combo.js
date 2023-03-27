import axios from '../app/api'

$.fn.childCombo = function (options) {
  const preparedOptions = {
    dataIndex: 'parent_id',
    resultIndex: 'data',
    hideEmpty: false,
    allowEmpty: false,
    emptyItem: '',
    disabledText: '',
    emptyText: 'Пусто',
    hidden: false,
    dateRange: false,
    ...options,
  }
  const parent = $(preparedOptions.parent)
  if (!parent.length) {
    return $(this)
  }

  let child = $(this)
  const isMultiple = child.attr('multiple')

  if (child.is('input[type="hidden"]')) {
    preparedOptions.hidden = true
    const c = $(`<select class="form-select" name="${child.attr('name')}"></select>`)
    child.after(c)
    child.remove()
    child = c
  }

  const trigger = function (fn, arg) {
    if (!options[fn]) return
    options[fn].call(child, arg)
  }

  const onchange = function () {
    if (options.dateRange && parent.val().length < 14) return

    trigger('change')

    child.prop('disabled', true)
    const isEmpty = parent.val() === null || parent.val() === ''
    if (!options.allowEmpty && isEmpty) {
      child.html(`<option value="">${options.disabledText}</option>`)
      if (options.hideEmpty) {
        child.parent().hide()
      }
      trigger('load', [])
      return
    }

    child.parent().show()

    const value = options.value || child.val()
    let valTemp = []
    const data = $.extend({}, options.data)
    if (!isEmpty) {
      data[options.dataIndex] = parent.val()
    }
    if (value) {
      valTemp = isMultiple ? value : [value]
    }
    // delete options.value;

    child.html("<option value=''>Загрузка</option>")

    axios.get(options.url, { params: data }).then((result) => {
      child.html('')
      const items = result[options.resultIndex]
      const val = []; let i; const
        l = items.length
      if (l === 0) {
        if (options.emptyText !== false) child.append(`<option value="">${options.emptyText}</option>`)
        if (options.hideEmpty) child.parent().hide()
        trigger('load', items)
        return
      }

      if (options.emptyItem !== false) {
        child.append(`<option value="">${options.emptyItem}</option>`)
      }

      for (i = 0; i < l; i++) {
        if (in_array(items[i].id, valTemp)) {
          val[val.length] = items[i].id
        }

        child.append(`<option value='${items[i].id}'>${items[i].name}</option>`)
      }

      child.prop('disabled', false)

      if (val.length) {
        child.val(isMultiple ? val : val[0])
      }

      child.change()
      trigger('load', items)
    })
  }

  parent.change(onchange)

  onchange()

  return child
}
