export type ShowDataModalSettings = {
  controlSelector: string
  attributeName: string
  modalTitle: string
}

const showModal = (title: string, element: HTMLElement, attributeName: string) => {
  const modalContent = element.getAttribute(attributeName) || ''
  const $modal = $('<pre><pre>').html(modalContent)
  window.WindowDialog({
    title,
    html: $modal,
    buttons: [{ text: 'Скрыть', cls: 'btn btn-default', handler: 'cancel' }],
  })
}

export const showDataModal = (settings: ShowDataModalSettings[]) => {
  settings.forEach((control) => {
    if (!control.controlSelector) return
    $(control.controlSelector).on('click', (e: any) => {
      e.preventDefault()
      try {
        showModal(control.modalTitle, e.currentTarget as HTMLElement, control.attributeName)
      } catch (error) {
        console.error(error)
      }
    })
  })
}
