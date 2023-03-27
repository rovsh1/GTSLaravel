import FileManager from '../libs/filemanager/manager'

// eslint-disable-next-line no-multi-assign
window.$ = window.jQuery = require('jquery/dist/jquery')

require('jquery-ui/ui/widgets/autocomplete')
require('jquery-ui/ui/widgets/draggable')
require('jquery-ui/ui/widgets/droppable')

$(document).ready(() => {
  const fileManager = new FileManager(document.body, {})
  const urlString = window.location.href
  const url = new URL(urlString)
  const type = url.searchParams.get('type')
  if (type) fileManager.filterByType(type)
  fileManager.setView(url.searchParams.get('view') || 'grid')

  if (window.parent) {
    fileManager.bind('choose', (file) => {
      window.parent.postMessage({
        sender: 'filemanager',
        mimeType: file.mimeType,
        name: file.name,
        url: file.src,
      }, '*')
    })
  }
})

// import("./page/auth.js");
