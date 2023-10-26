import { HotelResponse, useHotelSearchAPI } from '~resources/api/hotel/get'

import './style.scss'

export function createHotelSwitcher(container: Element | HTMLElement, withMatgins: boolean = true) {
  let isFirstOpen = true
  if (!container) return
  const dropdownContainer = document.createElement('div')
  dropdownContainer.classList.add('hotel-switcher-container')
  dropdownContainer.setAttribute('id', 'hotel-switcher')

  const toggleButton = document.createElement('button')
  const toggleButtonIcon = document.createElement('i')
  toggleButtonIcon.classList.add('icon')
  toggleButtonIcon.innerHTML = 'grid_view'
  toggleButton.classList.add('hotel-switcher-toggle-button')
  toggleButton.style.marginRight = withMatgins ? '1rem' : '0'
  toggleButton.appendChild(toggleButtonIcon)

  const menuListContainer = document.createElement('div')
  menuListContainer.classList.add('hotel-switcher-menu-list-container')
  const menuList = document.createElement('div')
  menuList.classList.add('hotel-switcher-menu-list')

  const menuListLoader = document.createElement('div')
  menuListLoader.classList.add('hotel-switcher-loading')

  async function populateMenu() {
    const currentPath = location.pathname
    const regexForSearchHotelID = /(\/(hotels?|hotels?\/\w+)+\/)\d+/
    const match = currentPath.match(regexForSearchHotelID)
    let currentHotelID: string | null = null
    if (match) {
      const fullMatchPath = match[0] ? match[0] : ''
      const fullMatch = fullMatchPath.match(/\d+/)
      currentHotelID = fullMatch && fullMatch.length > 0 ? fullMatch[0].toString() : null
    }
    try {
      const {
        execute: fetchHotel,
        data: hotelData,
      } = useHotelSearchAPI({})
      await fetchHotel()
      const data = hotelData.value
      data?.forEach((item: HotelResponse) => {
        if (item.id.toString() === currentHotelID) return
        const menuItem = document.createElement('div')
        menuItem.classList.add('hotel-switcher-menu-list-item')
        menuItem.setAttribute('data-hotelid', item.id.toString())
        menuItem.innerText = `${item.name} (${item.city_name})`
        menuItem.addEventListener('click', () => {
          const newHotelID = menuItem.getAttribute('data-hotelid')
          const updatedString = location.pathname.replace(regexForSearchHotelID, `$1${newHotelID}`)
          location.href = updatedString
        })
        menuList.appendChild(menuItem)
      })
      menuListLoader.style.display = 'none'
    } catch (error) {
      menuListLoader.style.display = 'none'
    }
  }

  function searchMenuItems(query: string) {
    const items = menuList.querySelectorAll('.hotel-switcher-menu-list-item')
    items.forEach((item) => {
      const itemSource = item as HTMLElement
      const text = itemSource.innerHTML.trim().toLowerCase()
      if (text.includes(query.trim().toLowerCase())) {
        itemSource.style.display = 'block'
      } else {
        itemSource.style.display = 'none'
      }
    })
  }

  toggleButton.addEventListener('click', () => {
    if (isFirstOpen) {
      populateMenu()
      isFirstOpen = false
    }
    if (menuListContainer.style.display === 'block') {
      menuListContainer.style.display = 'none'
    } else {
      menuListContainer.style.display = 'block'
    }
  })

  const searchInputContainer = document.createElement('div')
  searchInputContainer.classList.add('hotel-switcher-search-container')
  const searchInput = document.createElement('input')
  searchInput.classList.add('form-control')
  searchInput.placeholder = 'Поиск'
  searchInput.addEventListener('input', (event) => {
    const query = (event.target as HTMLInputElement).value
    searchMenuItems(query)
  })

  searchInputContainer.appendChild(searchInput)
  menuListContainer.appendChild(searchInputContainer)
  menuListContainer.appendChild(menuList)
  menuListContainer.appendChild(menuListLoader)

  dropdownContainer?.appendChild(toggleButton)
  dropdownContainer?.appendChild(menuListContainer)

  const firstParentChild = container.firstChild
  if (firstParentChild) {
    container.insertBefore(dropdownContainer, firstParentChild)
  } else {
    container.appendChild(dropdownContainer)
  }

  document.addEventListener('click', (event) => {
    const isClickInsideMenu = menuListContainer.contains(event.target as Node)
    const isClickInsideToggleButton = toggleButton.contains(event.target as Node)

    if (!isClickInsideMenu && !isClickInsideToggleButton) {
      menuListContainer.style.display = 'none'
    }
  })
}
