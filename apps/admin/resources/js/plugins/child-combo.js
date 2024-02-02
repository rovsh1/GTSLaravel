import axios from '~resources/js/app/api'
import { useSelectElement } from '~widgets/select-element/select-element'

$.fn.childCombo = async function (options) {
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
  let currentParentValue = undefined
  const parent = $(preparedOptions.parent)
  if (!parent.length) {
    return $(this).attr('id')
  }
  parent.addClass('child-combo-parent-element')
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
        required: child.attr('required'),
        multiple: 'multiple',
        disabled: childDisabled
      })
    } else {
      c = $(`<select />`, {
        class: 'form-select',
        id: child.attr('id'),
        name: child.attr('name'),
        required: child.attr('required'),
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

  const setSelect2PlaceholderValue = function (value) {
    child.next().find(".select2-selection__rendered").text(value)
  }

  const onchange = async function () {
    if ((preparedOptions.dateRange && parent.val().length < 14) || currentParentValue === parent.val()) {
      return
    }
    currentParentValue = parent.val()
    trigger('change')

    child.prop('disabled', true)
    const isEmpty = parent.val() === null || parent.val() === ''

    if (isEmpty) {
      child.val([])
      child.change()
      setTimeout(() => {
        setSelect2PlaceholderValue(preparedOptions.disabledText)
      }, 0)

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
      child.val([])
      child.change()
      trigger('load', [])
      return
    }

    setSelect2PlaceholderValue('Загрузка')

    let url = preparedOptions.url
    if (preparedOptions.urlGetter) {
      url = preparedOptions.urlGetter(parent.val())
    }
    axios.get(url, { params: data }).then(async (result) => {
      child.html('')
      const items = result[preparedOptions.resultIndex]
      const val = [];
      let i;
      const l = items.length
      if (l === 0) {
        if (preparedOptions.hideEmpty) {
          child.parent().hide()
        }
        if (preparedOptions.allowEmpty) {
          child.append(`<option value=''>${preparedOptions.emptyItem}</option>`)
        }
        child.prop('disabled', false)
        await useSelectElement(child[0], {
          multiple: isMultiple,
        })
        if (!preparedOptions.allowEmpty) {
          setSelect2PlaceholderValue(preparedOptions.emptyText)
        }
        if (preparedOptions.allowEmpty && preparedOptions.emptyItem !== '' && !isMultiple) {
          setTimeout(() => {
            child.val('').change()
          }, 0)
        }
        trigger('load', items)
        return
      }

      if (preparedOptions.allowEmpty) {
        child.append(`<option value=''>${preparedOptions.emptyItem}</option>`)
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
      await useSelectElement(child[0], {
        multiple: isMultiple,
      })
      if (val.length) {
        child.val(isMultiple ? val : val[0])
      } else {
        if (preparedOptions.allowEmpty && preparedOptions.emptyItem !== '') {
          child.val('')
        } else {
          child.val([])
        }
      }
      child.change()
      trigger('load', items)
    }).catch(async (error) => {
      child.html('')
      if (preparedOptions.allowEmpty) {
        child.append(`<option value=''>${preparedOptions.emptyItem}</option>`)
      }
      child.prop('disabled', false)
      await useSelectElement(child[0], {
        multiple: isMultiple,
      })
      if (!preparedOptions.allowEmpty) {
        setSelect2PlaceholderValue(preparedOptions.emptyText)
      }
      if (preparedOptions.allowEmpty && preparedOptions.emptyItem !== '' && !isMultiple) {
        setTimeout(() => {
          child.val('').change()
        }, 0)
      }
      trigger('load', [])
      return
    })
  }

  parent.change(onchange)

  await useSelectElement(child[0], {
    multiple: isMultiple,
  })
  onchange()
  return child
}