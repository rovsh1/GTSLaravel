const isTypeFn = {
  is_date(value) { return toString.call(value) === '[object Date]' },

  is_array: ('isArray' in Array) ? Array.isArray : function (value) { return toString.call(value) === '[object Array]' },

  is_object: (toString.call(null) === '[object Object]')
    ? function (value) {
      return value !== null && value !== undefined && toString.call(value) === '[object Object]' && value.ownerDocument === undefined
    } : function (value) {
      return toString.call(value) === '[object Object]'
    },

  is_function:
        (typeof document !== 'undefined' && typeof document.getElementsByTagName('body') === 'function') ? function (value) {
          return !!value && toString.call(value) === '[object Function]'
        } : function (value) {
          return !!value && typeof value === 'function'
        },

  is_simple_object(value) { return value instanceof Object && value.constructor === Object },

  is_scalar(value) {
    const type = typeof value

    return type === 'string' || type === 'number' || type === 'boolean'
  },

  is_number(value) { return typeof value === 'number' && isFinite(value) },

  is_numeric(value) { return !isNaN(parseFloat(value)) && isFinite(value) },

  is_string(value) { return typeof value === 'string' },

  is_boolean(value) { return typeof value === 'boolean' },

  is_empty(value, allowEmptyString) {
    return (value === null) || (value === undefined) || (!allowEmptyString ? value === '' : false) || (this.is_array(value) && value.length === 0)
  },
}

const coreFn = {
  EmptyFn() {},

  in_array(value, array) { return !!array.find((v) => v === value) },

  str_pad(input, length, padString) {
    // eslint-disable-next-line no-param-reassign
    input = input.toString()
    if (input.length >= length) return input

    const s = []
    const l = length - input.length
    for (let i = 0; i < l; i++) {
      s[s.length] = padString
    }
    // eslint-disable-next-line no-param-reassign
    input = s.join('') + input

    return input
  },

  ucfirst(str) { return str[0].toUpperCase() + str.substring(1, str.length) },

  explode(delimiter, string) { return string.toString().split(delimiter.toString()) },

  htmlspecialchars(text) {
    if (!text) return ''
    const map = {
      '&': '&amp;',
      '<': '&lt;',
      '>': '&gt;',
      '"': '&quot;',
      "'": '&#039;',
    }
    return text.replace(/[&<>"']/g, (m) => map[m])
  },

  getWordDeclension(number, variants) {
    // eslint-disable-next-line no-param-reassign
    number = Math.abs(number)
    let i
    switch (true) {
      case (number % 100 === 1 || (number % 100 > 20) && (number % 10 === 1)):
        i = 0
        break
      case (number % 100 === 2 || (number % 100 > 20) && (number % 10 === 2)):
      case (number % 100 === 3 || (number % 100 > 20) && (number % 10 === 3)):
      case (number % 100 === 4 || (number % 100 > 20) && (number % 10 === 4)):
        i = 1
        break
      default:
        i = 2
    }
    // eslint-disable-next-line no-param-reassign
    if (typeof (variants) === 'string') variants = variants.split(',')

    return variants[i] || variants[0]
  },

  // eslint-disable-next-line consistent-return
  call_user_func(callable, arg) {
    if (isTypeFn.is_function(callable)) {
      return callable(arg)
    } if (isTypeFn.is_array(callable)) {
      const s = callable[0].split('.')
      const l = s.length
      let h = window
      for (let i = 0; i < l; i++) {
        // eslint-disable-next-line consistent-return
        if (!h[s[i]]) return
        h = h[s[i]]
      }
      if (isTypeFn.is_function(h)) return h(callable[1] || arg)
    }
  },

  now() {
    return new Date()
  },
}

const hashFn = {
  rand(min, max) {
    return min + Math.round((max - min) * Math.random())
  },

  generateHash(length) {
    const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ123456789'
    const l = chars.length - 1
    let hash = ''
    for (let i = 0; i < length; i++) {
      hash += chars.substr(this.rand(0, l), 1)
    }
    return hash
  },
}

export default Object.assign(window, isTypeFn, coreFn, hashFn)
