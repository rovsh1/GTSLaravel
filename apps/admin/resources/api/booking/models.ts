import { DateResponse } from '~api'

export type StatusID = number
export type BookingID = number
export type OrderID = number

export type BookingStatusResponse = {
  id: StatusID
  name: string
  color: string
}

export type ManualChangablePrice = {
  value: number
  isManual: boolean
}

export type BookingPrice = {
  boPrice: ManualChangablePrice
  hoPrice: ManualChangablePrice
  netValue: number
  hoPenalty: number | null
  boPenalty: number | null
}

export type BaseBooking = {
  id: BookingID
  orderId: OrderID
  status: BookingStatusResponse
  source: number
  creatorId: number
  createdAt: DateResponse
  note: string | null
  price: BookingPrice
}
