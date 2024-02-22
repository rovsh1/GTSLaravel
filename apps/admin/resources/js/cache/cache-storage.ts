interface CacheStorageInterface {
  has: (key: string) => boolean
  get: (key: string, def?: any) => any
  set: (key: string, value: any, ttl?: number | null) => void
  delete: (key: string) => void
  remember: (key: string, ttl: number, callback: () => any) => any
  rememberForever: (key: string, callback: () => any) => any
  clear: () => void
}

const CACHE_PREFIX = 'cache:'

const getCacheId = (key: string) => CACHE_PREFIX + key

const time = () => new Date().getTime()

const packData = (value: any, ttl: number | null) => JSON.stringify({
  expiresAt: ttl ? (time() + (ttl * 1000)) : null,
  value,
})

const unpackData = (payload: any) => JSON.parse(payload)

const getNotExpiredValue = (key: string) => {
  const cacheId = getCacheId(key)
  const packedData = localStorage.getItem(cacheId)
  if (!packedData) { return null }

  const data = unpackData(packedData)
  if (data.expiresAt && time() > data.expiresAt) {
    localStorage.removeItem(cacheId)
    return null
  }

  return data.value
}

const remember = async (key: string, ttl: number | null, callback: () => any) => {
  let value = getNotExpiredValue(key)
  if (value && value.length > 0) { return value }

  value = await callback()
  if (value === null || value.length < 1) { return null }

  localStorage.setItem(getCacheId(key), packData(value, ttl || null))

  return value
}

export const CacheStorage = {
  has: (key: string) => !!getNotExpiredValue(key),
  get: (key: string) => getNotExpiredValue(key),
  set: (key: string, value: any, ttl?: number | null) => {
    localStorage.setItem(getCacheId(key), packData(value, ttl || null))
  },
  delete: (key: string) => {
    localStorage.removeItem(getCacheId(key))
  },
  remember: (key: string, ttl: number, callback: () => any) => remember(key, ttl, callback),
  rememberForever: (key: string, callback: () => any) => remember(key, null, callback),
  clear: () => {
    const l = localStorage.length
    for (let i = l - 1; i >= 0; i--) {
      const key = localStorage.key(i)
      if (key?.indexOf(CACHE_PREFIX) === 0) { localStorage.removeItem(key) }
    }
  },
} as CacheStorageInterface
