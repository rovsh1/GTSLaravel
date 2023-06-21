import { DateResponse } from '~api'

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
  sign: string
}
