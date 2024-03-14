export type QuotasStatusUpdateFormData = {
  period: [Date, Date] | undefined
  daysWeekSelected: string[]
  selectedRoomsID: string[]
  action: Action | null
}

export type QuotasStatusUpdatePayload = {
  dateFrom: Date
  dateTo: Date
  daysWeekSelected: number[]
  selectedRoomsID: number[]
  action: Action
}

export type Action = 'open' | 'close'

export type ActionsOption = {
  value: Action
  label: string
}

export type HotelResponse = {
  id: number
  name: string
  city_id: number
  city_distance: number
  city_name: string
  address: string
  address_lat: number
  address_lon: number
  country_name: string
  zipcode: string
  rating: number | null
  status: number
  type_id: number
  type_name: string
  visibility: 0 | 1 | 2
  created_at: string
  updated_at: string
  deleted_at: string | null
}

export type HotelRoomQuotasUpdateBaseProps = {
  hotelID: number
  roomID: number
  dates: string[]
}

export type HotelRoomQuotasStatusUpdateKind = 'open' | 'close' | 'reset'

export type HotelRoomQuotasStatusUpdatePayload = HotelRoomQuotasUpdateBaseProps & {
  kind: HotelRoomQuotasStatusUpdateKind
}

export type HotelRoomQuotasCountUpdatePropsKind = {
  kind: 'count'
  count: number
}

export type HotelRoomReleaseDaysPropsKind = {
  kind: 'releaseDays'
  releaseDays: number
}

export type HotelRoomQuotasUpdatePayload<
  T = HotelRoomQuotasCountUpdatePropsKind | HotelRoomReleaseDaysPropsKind,
> = T & HotelRoomQuotasUpdateBaseProps

export type HotelRoomQuotasCountUpdateProps = HotelRoomQuotasUpdatePayload<HotelRoomQuotasCountUpdatePropsKind>
export type HotelRoomReleaseDaysUpdateProps = HotelRoomQuotasUpdatePayload<HotelRoomReleaseDaysPropsKind>

export type HotelRoom = {
  id: number
  hotelID: number
  name: string
  roomsNumber: number
  guestsCount: number
}

export type UseHotelRooms = HotelRoom[] | null

export type AvailabilityValue = 'sold' | 'stopped' | 'available'

export type AvailabilityOption = {
  value: AvailabilityValue
  label: string
}

export const availabilityOptions: AvailabilityOption[] = [
  { value: 'sold', label: 'Проданные' },
  { value: 'stopped', label: 'Остановленные' },
  { value: 'available', label: 'Доступные' },
]

export type FiltersPayload = {
  dateFrom: Date
  dateTo: Date
  availability?: AvailabilityValue | null
  roomID?: number | null
}

export type RoomQuotaStatus = 'opened' | 'closed' | 'warning'

export type RoomQuota = {
  key: string
  id: number | null
  roomID: number
  date: string
  status: RoomQuotaStatus | null
  quota: number | null
  sold: number | null
  reserve: number | null
  releaseDays: number | null
}

export type Day = {
  key: string
  date: string
  dayOfWeek: string
  dayOfMonth: string
  isHoliday: boolean
  monthKey: string
  monthName: string
  isLastDayInMonth: boolean
  dayQuota: RoomQuota | null
}

export type Month = {
  monthKey: string
  monthName: string
  daysCount: number
}

export type RoomRender = {
  id: number
  label: string
  guests: number
  count: number
}

export type QuotasAccumalationData = {
  period: Day[]
  months: Month[]
  quotas: Map<string, RoomQuota>
}

export type QuotaStatus = 0 | 1

export type HotelQuotaResponse = {
  id: number
  date: string
  room_id: number
  status: QuotaStatus
  release_days: number
  count_available: number
  count_total: number
  count_booked: number
  count_reserved: number
}

export type HotelQuota = Omit<HotelQuotaResponse, 'room_id'> & {
  roomID: number
}

export type UseHotelQuota = HotelQuota[] | null

export const quotaStatusMap: Record<QuotaStatus, RoomQuotaStatus | undefined> = {
  0: 'closed',
  1: 'opened',
}

export const monthKeyFormat = 'yyyy-M'
export const quotaDateFormat = 'yyyy-MM-dd'
