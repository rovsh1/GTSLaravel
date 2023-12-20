interface LocalStorageCacheSettings {
  key: string
  expirationTimeInMinutes: number
}

export const cacheSettings: LocalStorageCacheSettings[] = [{
  key: 'currencies',
  expirationTimeInMinutes: 1440,
}, {
  key: 'countries',
  expirationTimeInMinutes: 1440,
}, {
  key: 'booking-statuses',
  expirationTimeInMinutes: 1440,
}, {
  key: 'order-statuses',
  expirationTimeInMinutes: 1440,
}, {
  key: 'cities',
  expirationTimeInMinutes: 1440,
}, {
  key: 'cancel-reasons',
  expirationTimeInMinutes: 1440,
}]
