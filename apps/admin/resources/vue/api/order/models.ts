import { DateResponse } from '~api'

export type StatusID = number
export type OrderID = number

export type OrderStatusResponse = {
  id: StatusID
  name: string
  color: string
}

export type CurrencyDto = {
  id: number
  value: string
  name: string
}

export type PriceItem = {
  currency: CurrencyDto
  calculatedValue: number
  manualValue: number | null
  penaltyValue: number | null
  isManual: boolean
}

export type ProfitItem = {
  currency: CurrencyDto
  supplierValue: number
  clientValue: number
  profitValue: number
}

export type OrderPrice = {
  currency: CurrencyDto
  value: number
}

export type BaseOrder = {
  id: OrderID
  status: OrderStatusResponse
  source: number
  creatorId: number
  createdAt: DateResponse
  clientPrice: OrderPrice
  clientPenalty: OrderPrice | null
  note?: string | null
  externalId?: string | null
}
