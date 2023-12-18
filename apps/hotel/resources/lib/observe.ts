export const observeDynamicElements = (containerForWatch: HTMLElement | null, observableTag: string, observableTagEventName: string, callback: (event: any) => void) => {
  const isValidObservableTag = document.createElement(observableTag)
  if (!containerForWatch || !isValidObservableTag) return
  if (`on${observableTagEventName}` in isValidObservableTag) {
    const observer = new MutationObserver((mutationsList) => {
      mutationsList.forEach((mutation) => {
        if (mutation.type === 'childList') {
          Array.from(mutation.addedNodes).forEach((addedNode) => {
            if (addedNode instanceof HTMLElement && addedNode.tagName === observableTag.toUpperCase() && addedNode.getAttribute('name') !== '_method') {
              addedNode.addEventListener(observableTagEventName, (e) => {
                callback(e)
              })
            }
          })
        }
      })
    })
    observer.observe(containerForWatch, { childList: true, subtree: true })
  }
}
