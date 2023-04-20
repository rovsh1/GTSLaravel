import FileManager from '~resources/js/libs/file-manager/manager'

// eslint-disable-next-line @typescript-eslint/ban-ts-comment
// @ts-expect-error
await import('jquery-ui/dist/jquery-ui')
// eslint-disable-next-line @typescript-eslint/ban-ts-comment
// @ts-expect-error
await import('jquery-ui/ui/widgets/autocomplete')
// eslint-disable-next-line @typescript-eslint/ban-ts-comment
// @ts-expect-error
await import('jquery-ui/ui/widgets/draggable')
// eslint-disable-next-line @typescript-eslint/ban-ts-comment
// @ts-expect-error
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
      // eslint-disable-next-line @typescript-eslint/ban-ts-comment
      // @ts-expect-error
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
