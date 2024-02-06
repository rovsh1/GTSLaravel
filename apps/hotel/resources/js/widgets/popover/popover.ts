import './style.scss'

export type PopoverItem = {
  text: string
  buttonText: string
  callback: () => void
}

type PopoverOptions = {
  content: PopoverItem[]
  textForEmpty: string
  relationElement: HTMLElement | string
}

const createPopover = ({ content, relationElement, textForEmpty }: PopoverOptions) => {
  let popoverRef: HTMLElement | null = null
  let relationRef: HTMLElement | null = null
  let isVisible: boolean = false

  const togglePopoverStatus = () => isVisible

  const destroyPopover = () => {
    relationRef = relationRef as HTMLElement
    relationRef.parentNode?.removeChild(popoverRef as HTMLElement)
  }

  const handleClickOutside = (event: MouseEvent) => {
    if (popoverRef && !popoverRef.contains(event.target as Node)) {
      destroyPopover()
      document.removeEventListener('mousedown', handleClickOutside)
    }
  }

  const handleItemClick = (callback: () => void) => {
    callback()
  }

  const setPosition = () => {
    relationRef = relationElement as HTMLElement
    popoverRef = popoverRef as HTMLElement
    const positionRelationElement = relationRef.getBoundingClientRect()
    const positionpopoverRef = popoverRef.getBoundingClientRect()
    popoverRef.style.cssText = `left: ${positionRelationElement.right - positionpopoverRef.width}px; top: ${positionRelationElement.top + positionRelationElement.height}px`
  }

  const render = () => {
    if (!popoverRef) {
      popoverRef = document.createElement('div')
      popoverRef.classList.add('popover-container')
      if (popoverRef instanceof String) {
        relationRef = document.getElementById(relationElement as string) as HTMLElement | null
        if (relationRef) {
          relationRef.parentNode?.appendChild(popoverRef)
        } else {
          return
        }
      } else if (popoverRef instanceof HTMLElement) {
        relationRef = relationElement as HTMLElement
        relationRef.parentNode?.appendChild(popoverRef)
      } else {
        return
      }
    }
    if (content.length) {
      content.forEach((item) => {
        const itemElement = document.createElement('div')
        const linkElement = document.createElement('a')
        itemElement.classList.add('popover-item')
        itemElement.textContent = item.text
        linkElement.text = item.buttonText
        linkElement.addEventListener('click', (e) => {
          e.preventDefault()
          handleItemClick(item.callback)
        })
        itemElement.appendChild(linkElement)
        popoverRef?.appendChild(itemElement)
      })
    } else {
      const itemElement = document.createElement('div')
      itemElement.classList.add('popover-item', 'text-center')
      itemElement.textContent = textForEmpty
      popoverRef?.appendChild(itemElement)
    }
    setPosition()
    isVisible = true
  }

  const togglePopover = () => {
    render()
  }

  const handleWindowResize = () => {
    setPosition()
  }

  document.addEventListener('mousedown', handleClickOutside)
  window.addEventListener('resize', handleWindowResize)
  togglePopover()

  return {
    togglePopoverStatus,
    destroyPopover,
  }
}

export default createPopover
