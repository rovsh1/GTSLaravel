import { useAdminAPI } from '~api'
import { HotelID } from '~api/hotel/get'
import { Time } from '~api/hotel/markup-settings'

export interface TimeSettings {
  checkInAfter: Time
  checkOutBefore: Time
  breakfastFrom: Time | null
  breakfastTo: Time | null
}

export interface HotelSettings {
  id: HotelID
  name: string
  currency: string
  timeSettings: TimeSettings
}

export const useHotelSettingsAPI = () =>
  useAdminAPI({}, () =>
    '/hotel/settings/get')
    .get()
    .json<HotelSettings>()

export interface UpdateHotelSettingsPayload {
  hotelID: HotelID
  timeSettings: TimeSettings
}
