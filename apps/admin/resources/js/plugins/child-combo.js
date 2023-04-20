import axios from '~resources/js/app/api'

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
    if (!preparedOptions[fn]) return
    preparedOptions[fn].call(child, arg)
  }

  const onchange = function () {
    if (preparedOptions.dateRange && parent.val().length < 14) return

    trigger('change')

    child.prop('disabled', true)
    const isEmpty = parent.val() === null || parent.val() === ''
    if (!preparedOptions.allowEmpty && isEmpty) {
      child.html(`<option value="">${preparedOptions.disabledText}</option>`)
      if (preparedOptions.hideEmpty) {
        child.parent().hide()
      }
      trigger('load', [])
      return
    }

    child.parent().show()

    const value = preparedOptions.value || child.val()
    let valTemp = []
    const data = $.extend({}, preparedOptions.data)
    if (!isEmpty) {
      data[preparedOptions.dataIndex] = parent.val()
    }
    if (value) {
      valTemp = isMultiple ? value : [value]
    }
    // delete preparedOptions.value;

    child.html("<option value=''>Загрузка</option>")

    axios.get(preparedOptions.url, { params: data }).then((result) => {
      child.html('')
      const items = result[preparedOptions.resultIndex]
      const val = []; let i; const
        l = items.length
      if (l === 0) {
        if (preparedOptions.emptyText !== false) child.append(`<option value="">${preparedOptions.emptyText}</option>`)
        if (preparedOptions.hideEmpty) child.parent().hide()
        trigger('load', items)
        return
      }

      if (preparedOptions.emptyItem !== false) {
        child.append(`<option value="">${preparedOptions.emptyItem}</option>`)
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
