export interface PriceRate {
  id: number
  hotel_id: number
  name: string
  description: string
}

export interface Room {
  id: number
  hotel_id: number
  type_id: number
  rooms_number: number
  guests_count: number
  type_name: string
  name: string | null
  price_rates: PriceRate[]
}

export interface Season {
  id: number
  contract_id: number
  name: string
  date_start: string
  date_end: string
}

export interface RoomSeasonPrice {
  season_id: number
  room_id: number
  rate_id: number
  is_resident: boolean
  guests_count: number
  price: number
}

export interface PricesAccumulationData {
  id?: string
  hotelID: number
  roomID: number
  seasonID: number
  guestsCount: number
  rateID: number
  isResident?: boolean
  price: number | null
}
