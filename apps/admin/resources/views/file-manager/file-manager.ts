import FileManager from '~resources/js/libs/file-manager/manager'

await import('jquery-ui/dist/jquery-ui')
await import('jquery-ui/ui/widgets/autocomplete')
await import('jquery-ui/ui/widgets/draggable')
await import('jquery-ui/ui/widgets/droppable')

$(document)
  .ready(() => {
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
