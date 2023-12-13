import '~resources/views/main'

$(() => {
  const showModal = (title: string, element: HTMLElement) => {
    const modalContent = element.getAttribute('data-content') || ''
    const $modal = $('<pre><pre>').html(modalContent)
    window.WindowDialog({
      title,
      html: $modal,
      buttons: [{ text: 'Скрыть', cls: 'btn btn-default', handler: 'cancel' }],
    })
  }

  $('.payload .btn-data-content').on('click', (e: any) => {
    e.preventDefault()
    showModal('Описание', e.currentTarget as HTMLElement)
  })

  $('.context .btn-data-content').on('click', (e: any) => {
    e.preventDefault()
    showModal('Информация', e.currentTarget as HTMLElement)
  })
})
