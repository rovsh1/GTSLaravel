interface LocalStorageCache {
  hasData: () => boolean
  setData: (value: any) => void
  getData: () => any | undefined
}

export enum TTLValues {
  MONTH = 2628288,
  WEEK = 604800,
  DAY = 86400,
  HOUR = 3600,
  MINUTE = 60,
}

export const useLocalStorageCache = (key: string, expirationTimeInSeconds: TTLValues) => {
  let data: any
  let existData: boolean = false

  const loadData = () => {
    const storedData = localStorage.getItem(key)
    if (storedData) {
      try {
        const parsedData = JSON.parse(storedData)
        const { timestamp, value } = parsedData
        const currentTime = new Date().getTime()
        const isDataValid = !timestamp || (currentTime - timestamp) < expirationTimeInSeconds * 1000
        if (isDataValid && value) {
          data = value
          existData = true
          return
        }
        localStorage.removeItem(key)
      } catch (error) {
        console.error('Error parsing localStorage data:', error)
      }
    }
    existData = false
    data = undefined
  }

  const setData = (value: any) => {
    const dataToStore = {
      timestamp: new Date().getTime(),
      value,
    }
    localStorage.setItem(key, JSON.stringify(dataToStore))
    loadData()
  }

  const getData = () => data

  const hasData = () => existData

  loadData()

  return { hasData, setData, getData } as LocalStorageCache
}
