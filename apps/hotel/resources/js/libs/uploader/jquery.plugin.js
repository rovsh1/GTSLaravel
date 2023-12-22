import FilePlugin from './plugin'

$.fn.fileupload = function (options) {
  return $(this).each(function () {
    const input = $(this)
    const o = {
      multiple: input.attr('multiple'),
      input,
      ...options,
    }

    const plugin = new FilePlugin(o)

    input.parent()
      .append(plugin.el)
      .find('input[type="hidden"]')
      .each(function () { plugin.appendFile(JSON.parse(this.value)) })
    // .remove()

    input.data('plugin', plugin)
  })
}