import './style.scss'

type SwitcherOptions = {
  apiURL: string
  container: HTMLElement | string
}

type SwitcherItem = {
  id: number
  text: string
}

// Функция для создания выпадающего меню
export const createDropdown = (apiUrl: string) => {
  // Создаем основной контейнер для выпадающего меню
  const dropdownContainer = document.createElement('div')
  dropdownContainer.classList.add('dropdown-container')

  // Создаем кнопку
  const toggleButton = document.createElement('button')
  toggleButton.innerText = 'Открыть меню'
  toggleButton.classList.add('toggle-button')

  // Создаем список элементов выпадающего меню
  const menuList = document.createElement('ul')
  menuList.classList.add('menu-list')

  // Флаг для отслеживания состояния меню (открыто или закрыто)
  let isMenuOpen = false

  // Функция для загрузки данных из API
  const loadDataFromApi = async (): Promise<any> => {
    try {
      const response = await fetch(apiUrl)
      const data = await response.json()
      return data
    } catch (error) {
      console.error('Ошибка при получении данных из API:', error)
      return []
    }
  }

  // Функция для заполнения выпадающего меню данными
  const populateMenu = async () => {
    if (!isMenuOpen) {
      const data = await loadDataFromApi()

      data.forEach((item: string) => {
        const menuItem = document.createElement('li')
        menuItem.innerText = item
        menuList.appendChild(menuItem)
      })

      isMenuOpen = true
    }
  }

  // Обработчик события клика по кнопке
  toggleButton.addEventListener('click', () => {
    if (menuList.style.display === 'block') {
      menuList.style.display = 'none'
    } else {
      menuList.style.display = 'block'
      // Загружаем данные из API при первом открытии
      populateMenu()
    }
  })

  // Добавляем элементы в контейнер
  dropdownContainer.appendChild(toggleButton)
  dropdownContainer.appendChild(menuList)

  // Возвращаем созданный компонент
  return dropdownContainer
}
