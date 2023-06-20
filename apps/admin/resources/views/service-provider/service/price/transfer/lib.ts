import { DateResponse } from '~api'

export interface Car {
  id: number
  mark: string
  model: string
}

export interface Season {
  id: number
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
