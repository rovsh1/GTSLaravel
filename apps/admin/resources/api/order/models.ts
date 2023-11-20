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
  clientPrice: PriceItem
  supplierPrice: PriceItem
  profit: ProfitItem
}

export type BaseOrder = {
  id: OrderID
  status: OrderStatusResponse
  source: number
  creatorId: number
  createdAt: DateResponse
  currency?: CurrencyDto
  prices?: OrderPrice
  note?: string | null
}