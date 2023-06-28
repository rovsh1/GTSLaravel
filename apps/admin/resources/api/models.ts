import { CityResponse } from '~api/city'
import { DateResponse } from '~api/index'

export interface Airport {
  id: number
  city_id: number
  code: string
  name: string
  city_name: string
}

export interface Car {
  id: number
  mark: string
  model: string
  cities?: CityResponse[]
}

export interface Season {
  id: number
  number: string
  provider_id: number
  date_start: DateResponse
  date_end: DateResponse
}

export interface Service {
  id: number
  provider_id: number
  name: string
  type: number
}

export interface Currency {
  id: number
  code_num: number
  code_char: string
  name: string
  sign: string
}
