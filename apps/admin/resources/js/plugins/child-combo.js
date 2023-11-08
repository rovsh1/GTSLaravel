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
    useSelect2: false,
    ...options,
  }
  const parent = $(preparedOptions.parent)
  if (!parent.length) {
    return $(this)
  }

  let child = $(this)
  const isMultiple = child.attr('multiple')
  const value = preparedOptions.value || child.val()
  let valTemp = []
  if (value) {
    valTemp = isMultiple ? (value ? value.split(',') : []) : [value]
  }

  const childDisabled = child.prop("disabled")
  if (child.is('input[type="hidden"]')) {
    preparedOptions.hidden = true
    let c = null
    if (isMultiple) {
      c = $(`<select />`, {
        class: 'form-select',
        id: child.attr('id'),
        name: child.attr('name'),
        multiple: 'multiple',
        disabled: childDisabled
      })
    } else {
      c = $(`<select />`, {
        class: 'form-select',
        id: child.attr('id'),
        name: child.attr('name'),
        disabled: childDisabled
      })
    }

    child.after(c)
    child.remove()
    child = c
  }

  const trigger = function (fn, arg) {
    if (!preparedOptions[fn]) {
      return
    }
    preparedOptions[fn].call(child, arg)
  }

  child.on('change', (e) => {
    trigger('childChange', e)
  })

  const setSelect2MultiplePlaceholderWidth = function () {
    child.parent().find('.select2 > .selection').css('display', 'flex')
    child.next().find(".select2-search__field").attr('style', 'width: auto;')
  }

  const setSelect2MultiplePlaceholderValue = function (value) {
    child.next().find(".select2-search__field").attr('placeholder', value)
  }

  const onchange = function () {
    if (preparedOptions.useSelect2 && isMultiple) {
      setSelect2MultiplePlaceholderWidth()
    }

    if (preparedOptions.dateRange && parent.val().length < 14) {
      return
    }

    trigger('change')

    child.prop('disabled', true)
    const isEmpty = parent.val() === null || parent.val() === ''

    if (isEmpty) {
      if (preparedOptions.useSelect2 && isMultiple) {
        child.val([])
        child.change()
        setSelect2MultiplePlaceholderWidth()
        setSelect2MultiplePlaceholderValue(preparedOptions.disabledText)
      } else {
        child.html(`<option value="">${preparedOptions.disabledText}</option>`)
      }
      if (preparedOptions.hideEmpty) {
        child.parent().hide()
      }
      trigger('load', [])
      return
    }

    child.parent().show()

    const data = { ...preparedOptions.data }
    if (!isEmpty) {
      data[preparedOptions.dataIndex] = parent.val()
    } else {
      if (preparedOptions.useSelect2 && isMultiple) {
        child.val([])
      } else {
        child.val('')
      }
      child.change()
      return
    }

    if (preparedOptions.useSelect2 && isMultiple) {
      setSelect2MultiplePlaceholderValue('Загрузка')
    } else {
      child.html("<option value=''>Загрузка</option>")
    }

    let url = preparedOptions.url
    if (preparedOptions.urlGetter) {
      url = preparedOptions.urlGetter(parent.val())
    }

    axios.get(url, { params: data }).then((result) => {
      child.html('')
      if (preparedOptions.useSelect2 && isMultiple) {
        child.select2()
        setSelect2MultiplePlaceholderWidth()
        setSelect2MultiplePlaceholderValue('')
      }
      const items = result[preparedOptions.resultIndex]
      const val = [];
      let i;
      const l = items.length
      if (l === 0) {
        if (preparedOptions.emptyText !== false) {
          if (preparedOptions.useSelect2 && isMultiple) {
            setSelect2MultiplePlaceholderValue(preparedOptions.emptyText)
          } else {
            child.append(`<option value="">${preparedOptions.emptyText}</option>`)
          }
        }
        if (preparedOptions.hideEmpty) {
          child.parent().hide()
        }
        trigger('load', items)

        if (!preparedOptions.allowEmpty) {
          return
        }
      }

      if (preparedOptions.emptyItem !== false) {
        if (preparedOptions.useSelect2 && isMultiple) {
          setSelect2MultiplePlaceholderValue(preparedOptions.emptyItem)
        } else {
          child.append(`<option value="" ${!preparedOptions.allowEmpty ? 'disabled' : ''} selected>${preparedOptions.emptyItem}</option>`)
        }
      }
      for (i = 0; i < l; i++) {
        if (in_array(`${items[i].id}`, valTemp)) {
          val[val.length] = items[i].id
        }

        let name = items[i].name
        if (preparedOptions.labelGetter) {
          name = preparedOptions.labelGetter(items[i])
        }

        child.append(`<option value='${items[i].id}'>${name}</option>`)
      }

      if (!childDisabled) {
        child.prop('disabled', false)
      }


      if (val.length) {
        child.val(isMultiple ? val : val[0])
      } else {
        if (preparedOptions.useSelect2 && isMultiple) {
          child.val([])
        }
      }
      child.change()
      trigger('load', items)
    })
  }

  parent.change(onchange)

  onchange()

  if (preparedOptions.useSelect2) {
    child.select2()
    setSelect2MultiplePlaceholderWidth()
  }
  return child
}