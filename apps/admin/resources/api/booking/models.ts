import { DateResponse } from '~api'
import { CancelConditions } from '~api/booking/hotel/details'

export type StatusID = number
export type BookingID = number
export type OrderID = number

export type BookingStatusResponse = {
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

export type BookingPrice = {
  grossPrice: PriceItem
  netPrice: PriceItem
  profit: PriceItem
  convertedNetPrice: PriceItem | null
}

export type BaseBooking = {
  id: BookingID
  orderId: OrderID
  status: BookingStatusResponse
  source: number
  creatorId: number
  createdAt: DateResponse
  price: BookingPrice
  cancelConditions: CancelConditions
  note: string | null
}
