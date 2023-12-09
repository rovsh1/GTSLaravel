import { onMounted, ref } from 'vue'

import { cacheSettings } from './settings'

const useLocalStorageCache = (key: string) => {
  const data = ref()
  const existData = ref<boolean>(false)

  const loadFromLocalStorage = () => {
    if (!cacheSettings) {
      existData.value = false
      return
    }
    const storedData = localStorage.getItem(key)
    const settings = cacheSettings.find((option) => option.key === key)
    if (storedData && settings) {
      try {
        const parsedData = JSON.parse(storedData)
        const { timestamp, value } = parsedData
        const currentTime = new Date().getTime()
        const isDataValid = !timestamp || (currentTime - timestamp) < settings.expirationTimeInMinutes * 60 * 1000

        if (isDataValid && value) {
          data.value = value
          existData.value = true
          return
        }
        localStorage.removeItem(key)
      } catch (error) {
        console.error('Error parsing localStorage data:', error)
      }
    } else if (storedData) {
      localStorage.removeItem(key)
    }
    existData.value = false
    data.value = undefined
  }

  const saveToLocalStorage = (value: any) => {
    const dataToStore = {
      timestamp: new Date().getTime(),
      value,
    }
    localStorage.setItem(key, JSON.stringify(dataToStore))
  }

  onMounted(() => {
    loadFromLocalStorage()
  })

  return { data, existData, saveToLocalStorage, loadFromLocalStorage }
}

export default useLocalStorageCache
