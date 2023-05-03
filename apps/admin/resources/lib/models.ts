// "2021-10-05T14:55:20.000000Z"
type RDate = string

type CityID = number

type CountryID = number

type HotelID = string

type ContractID = number

type SeasonID = number

export type Hotel = {
  id: HotelID
  name: string
  city_id: CityID
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
  // TODO describe
  visibility: 0 | 1 | 2
  created_at: RDate
  updated_at: RDate
  deleted_at: RDate | null
}

export type HotelRoomResponse = {
  id: string
  hotel_id: HotelID
  name: string
  custom_name: string
  rooms_number: number
  guests_number: number
}

export type File = {
  guid: string
  name: string
  url: string
  entity_id: number
}

export type Season = {
  id: SeasonID
  contract_id: ContractID
  name: string
  date_start: RDate
  date_end: RDate
}

export type Contract = {
  id: ContractID
  date_start: RDate
  date_end: RDate
  seasons?:Season[]
}

export type City = {
  id: CityID
  name: string
  country_id: CountryID
  country_name?: string
  center_lat: number
  center_lon: number
}

export type MonthNumber = 1 | 2 | 3 | 4 | 5 | 6 | 7 | 8 | 9 | 10 | 11 | 12

// https://www.php.net/manual/en/dateinterval.format.php
export type QueryInterval =
  | 'P1M' // 1 month
  | 'P3M' // 3 months
  | 'P6M' // 6 months
  | 'P1Y' // 1 year
  | 'P2Y' // 2 years
  | 'P3Y' // 3 years
