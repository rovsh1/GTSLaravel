export type PriceRate = {
  id: number
  hotel_id: number
  name: string
  description: string
}

export type Room = {
  id: number
  hotel_id: number
  type_id: number
  rooms_number: number
  guests_count: number
  type_name: string
  name: string | null
  price_rates: PriceRate[]
}

export type Season = {
  id: number
  contract_id: number
  name: string
  date_start: string
  date_end: string
}

export type RoomSeasonPrice = {
  season_id: number
  room_id: number
  rate_id: number
  is_resident: boolean
  guests_count: number
  price: number | null
  has_date_prices: boolean
}

export type PricesAccumulationData = {
  id?: string
  hotelID: number
  roomID: number
  seasonID: number
  seasonStatusFlag: boolean | null
  guestsCount: number
  rateID: number
  rateName?: string
  isResident?: boolean
  price: number | null
}

export type PricesAccumulationDataForDays = PricesAccumulationData & {
  date?: string
  dayOfWeek?: string
  dayNumberTitle?: string
}

export type DateTime = string

export type SeasonPeriod = {
  from: DateTime
  to: DateTime
}

export type SeasonUpdateFormData = {
  period: [Date, Date]
  daysWeekSelected: string[]
  price: string
}
