import { DateResponse } from '~api'

export interface Season {
  id: number
  number: string
  supplier_id: number
  date_start: DateResponse
  date_end: DateResponse
}

export interface Service {
  id: number
  supplier_id: number
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

export interface Money {
  amount?: number
  currency?: string
}
