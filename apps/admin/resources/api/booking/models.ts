import { DateResponse } from '~api'
import { CancelConditions } from '~api/booking/hotel/details'

export type StatusID = number
export type BookingID = number
export type OrderID = number

export type BookingStatusResponse = {
  id: StatusID
  name: string
  color: string
  reason: string | null
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

export type BookingPrice = {
  clientPrice: PriceItem
  supplierPrice: PriceItem
  profit: ProfitItem
}

export type BaseBooking = {
  id: BookingID
  link: string
  orderId: OrderID
  status: BookingStatusResponse
  source: number
  creatorId: number
  createdAt: DateResponse
  prices: BookingPrice
  cancelConditions: CancelConditions | null
  note: string | null
}
