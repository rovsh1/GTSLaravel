import '~resources/views/main'

$(() => {
  $('.btn-data-content').on('click', (e: any) => {
    e.preventDefault()
    const modalTitle = $(e.currentTarget).data('modal-title') || '-'
    const modalContent = $(e.currentTarget).data('content') || ''
    const formattedModalContent = JSON.stringify(modalContent, null, 2)
    const $modal = $('<pre><pre>').html(formattedModalContent)
    window.WindowDialog({
      title: modalTitle,
      html: $modal,
      buttons: [{ text: 'Скрыть', cls: 'btn btn-default', handler: 'cancel' }],
    })
  })
})
