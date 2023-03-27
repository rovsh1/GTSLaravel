export default {
  bind(event, callback, scope) {
    if (undefined === this.__eventHandlers) this.__eventHandlers = []

    this.__eventHandlers.push([event, callback, scope])
    return this
  },

  unbind(event, callback) {
    if (undefined === this.__eventHandlers) return this

    event.split(' ')
      .forEach((event) => {
        const findFn = undefined === callback
          ? (h) => h[0] === event
          : (h) => h[0] === event && h[1] === callback
        let i = this.__eventHandlers.findIndex(findFn)
        while (i > -1) {
          this.__eventHandlers.splice(i, 1)
          i = this.__eventHandlers.findIndex(findFn)
        }
      })

    return this
  },

  trigger(event, ...args) {
    if (undefined === this.__eventHandlers) return

    const eventHandlers = this.__eventHandlers
      .filter((h) => h[0] === event)

    const l = eventHandlers.length
    for (let i = 0; i < l; i++) {
      if (eventHandlers[i][1].apply(eventHandlers[i][2] || this, args) === false) return
    }
  },

  bindOptions(options, optionsEvents) {
    if (!options) return

    optionsEvents
      .filter((n) => is_function(options[n]))
      .forEach((n) => {
        this.bind(n, options[n])
        delete options[n]
      })

    return this
  },
}
